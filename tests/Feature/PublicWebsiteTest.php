<?php

namespace Tests\Feature;

use App\Mail\ArchitectureInquiry;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_public_pages_render_approved_content_and_security_headers(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Secure infrastructure.')
            ->assertSee('Suite 5, 5160 Explorer Drive')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Content-Security-Policy');

        $this->get('/infrastructure')
            ->assertOk()
            ->assertSee('Routing &amp; Switching', false)
            ->assertSee('Email Edge Protection');

        $this->get('/cloud-software')
            ->assertOk()
            ->assertSee('IaaS &amp; Managed VPS', false)
            ->assertSee('Oracle APEX');
    }

    public function test_home_renders_its_reference_drawing_and_published_claims(): void
    {
        $this->get('/')
            ->assertOk()
            // The hero schematic is a real inline drawing, not a decorative image.
            ->assertSee('class="schematic"', false)
            ->assertSee('Figure 01')
            ->assertSee('TOP-01')
            ->assertSee('Enterprise SLA backed')
            ->assertSee('24/7 monitoring')
            ->assertSee('No vendor lock-in')
            ->assertSee('Sovereign asset control')
            ->assertSee('PHASE 01 — ASSESS')
            // The operations cycle: a relationship the claims list cannot show.
            ->assertSee('Figure 10')
            ->assertSee('OPS-01')
            ->assertSee('Remediate')
            ->assertSee('CONTINUOUS')
            // The technology set is drawn as a plate rather than tabulated.
            ->assertSee('Figure 11')
            ->assertSee('TECH-01')
            ->assertSee('TECHNOLOGY IN OPERATION · FIVE DOMAINS')
            ->assertSee('CROWDSTRIKE · SOPHOS · IMUNIFY360 · MICROSOFT DEFENDER · IDS/IPS');
    }

    public function test_service_pages_render_their_reference_drawings(): void
    {
        $this->get('/infrastructure')
            ->assertOk()
            ->assertSee('Figure 02')
            ->assertSee('SEC-01')
            ->assertSee('PERIMETER')
            ->assertSee('Figure 06')
            ->assertSee('MAP-01')
            ->assertSee('Figure 07')
            ->assertSee('REV-01');

        $this->get('/cloud-software')
            ->assertOk()
            ->assertSee('Figure 03')
            ->assertSee('STACK-01')
            ->assertSee('CUTOVER PATH')
            ->assertSee('Figure 08')
            ->assertSee('MAP-02')
            ->assertSee('Figure 09')
            ->assertSee('CUT-01');
    }

    /**
     * SVG text does not reflow. A label is drawn where it is placed, so a string
     * longer than its plate runs under the next one and no browser will fix it.
     *
     * Each drawing declares data-label-budget: the character count its plates were
     * measured to hold at the size its value text is set. This test holds every
     * drawing to its own declaration, so widening a string fails here rather than
     * on the rendered page.
     */
    public function test_drawing_labels_stay_inside_their_declared_budget(): void
    {
        $checked = 0;

        foreach (['/', '/infrastructure', '/cloud-software'] as $route) {
            $html = $this->get($route)->assertOk()->getContent();

            preg_match_all('#<svg([^>]*)>(.*?)</svg>#s', $html, $drawings, PREG_SET_ORDER);

            foreach ($drawings as [, $attributes, $body]) {
                if (! str_contains($attributes, 'class="schematic"') || ! str_contains($attributes, 'data-label-budget')) {
                    continue;
                }

                preg_match('#data-label-budget="(\d+)"#', $attributes, $budget);
                $budget = (int) $budget[1];
                $this->assertGreaterThan(0, $budget, $route.' declares no label budget.');

                preg_match_all('#<text class="value"[^>]*>([^<]*)</text>#', $body, $labels);

                foreach ($labels[1] as $label) {
                    $label = html_entity_decode($label);
                    $checked++;

                    $this->assertLessThanOrEqual(
                        $budget,
                        mb_strlen($label),
                        $route.' draws a label wider than its plate: "'.$label.'" ('.mb_strlen($label).' of '.$budget.' characters).'
                    );
                }
            }
        }

        $this->assertGreaterThan(20, $checked, 'The drawings rendered almost no measurable labels, so the check proved nothing.');
    }

    /**
     * Component attributes and component arrays are escaped once by Blade. An
     * entity written into one of those values is therefore printed literally, so
     * the pages are checked for the doubled form rather than for the entity.
     */
    public function test_no_page_prints_a_double_escaped_entity(): void
    {
        foreach (['/', '/infrastructure', '/cloud-software'] as $route) {
            $html = $this->get($route)->assertOk()->getContent();

            $this->assertStringNotContainsString('&amp;amp;', $html, $route.' prints a doubled ampersand entity.');
            $this->assertStringNotContainsString('&amp;quot;', $html, $route.' prints a doubled quote entity.');
            $this->assertStringNotContainsString('&amp;lt;', $html, $route.' prints a doubled angle-bracket entity.');
        }
    }

    public function test_legacy_urls_redirect_to_the_new_routes(): void
    {
        $this->get('/index.html')->assertRedirect('/');
        $this->get('/infrastructure.html')->assertRedirect('/infrastructure');
        $this->get('/cloud-software.html')->assertRedirect('/cloud-software');
    }

    public function test_unknown_url_has_a_useful_branded_response(): void
    {
        $this->get('/missing-page')
            ->assertNotFound()
            ->assertSee('This page is not available.')
            ->assertSee('Return to company overview');
    }

    public function test_inquiry_requires_valid_approved_fields(): void
    {
        $this->from('/#discussion')->post('/inquiries', [
            'email' => 'not-an-email',
            'interest' => 'Unsupported service',
            'message' => 'short',
        ])->assertRedirect('/#discussion')
            ->assertSessionHasErrors(['email', 'interest', 'message']);
    }

    public function test_valid_inquiry_is_delivered_to_every_configured_recipient(): void
    {
        Mail::fake();
        config(['tricetech.inquiry_recipients' => [
            'info@tricetech.ca',
            'junaid@tricetech.ca',
            'coo@tricetech.ca',
        ]]);

        $payload = [
            'email' => 'client@example.ca',
            'interest' => 'Enterprise Network Architecture',
            'message' => 'We need to review a multi-site network environment.',
        ];

        $this->post('/inquiries', $payload)
            ->assertRedirect(route('home', ['submitted' => 1]))
            ->assertSessionHas('inquiry_success');

        Mail::assertSentCount(1);

        Mail::assertSent(ArchitectureInquiry::class, function (ArchitectureInquiry $mail) use ($payload) {
            return $mail->hasTo('info@tricetech.ca')
                && $mail->hasTo('junaid@tricetech.ca')
                && $mail->hasTo('coo@tricetech.ca')
                && $mail->hasReplyTo($payload['email'])
                && $mail->hasSubject('Website inquiry: Enterprise Network Architecture')
                && $mail->inquiry === $payload;
        });
    }

    public function test_recipient_list_accepts_commas_spaces_semicolons_and_mixed_case(): void
    {
        $value = ' Info@Tricetech.ca, junaid@tricetech.ca;coo@tricetech.ca ';

        $this->assertSame(
            ['info@tricetech.ca', 'junaid@tricetech.ca', 'coo@tricetech.ca'],
            self::recipientsFor($value),
        );
    }

    public function test_recipient_list_falls_back_when_nothing_usable_is_configured(): void
    {
        $this->assertSame(
            ['info@tricetech.ca', 'junaid@tricetech.ca', 'coo@tricetech.ca'],
            self::recipientsFor('info@tricetech.ca,junaid@tricetech.ca,coo@tricetech.ca'),
        );

        $this->assertSame([], self::recipientsFor('  ,  '));
    }

    public function test_duplicate_inquiry_is_not_sent_twice(): void
    {
        Mail::fake();

        $payload = [
            'email' => 'client@example.ca',
            'interest' => '',
            'message' => 'Please review our upcoming cloud migration plan.',
        ];

        $this->post('/inquiries', $payload)->assertSessionHas('inquiry_success');
        $this->post('/inquiries', $payload)->assertSessionHas('inquiry_success');

        Mail::assertSentCount(1);
    }

    public function test_delivery_failure_preserves_input_and_excludes_it_from_logs(): void
    {
        Log::spy();
        Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('transport unavailable'));

        $payload = [
            'email' => 'client@example.ca',
            'interest' => 'Cloud Infrastructure (IaaS) Scaling',
            'message' => 'Please review our upcoming cloud migration plan.',
        ];

        $this->post('/inquiries', $payload)
            ->assertRedirect(route('home', ['delivery' => 'failed']))
            ->assertSessionHas('inquiry_error')
            ->assertSessionHasInput('email', $payload['email'])
            ->assertSessionHasInput('message', $payload['message']);

        Log::shouldHaveReceived('error')->once()->withArgs(function (string $message, array $context) use ($payload) {
            return $message === 'Inquiry delivery failed.'
                && ! in_array($payload['email'], $context, true)
                && ! in_array($payload['message'], $context, true);
        });
    }

    public function test_notification_body_prints_the_visitors_words_unescaped(): void
    {
        // Plain-text notification: escaped output would reach the inbox as
        // "Cloud &amp; Software" and "&lt;firewall&gt;".
        $body = (new ArchitectureInquiry([
            'email' => 'client@example.ca',
            'interest' => 'Cybersecurity & Server Protection Operations',
            'message' => 'We need help with R&D segmentation and <firewall> rules.',
        ]))->render();

        $this->assertStringContainsString('Cybersecurity & Server Protection Operations', $body);
        $this->assertStringContainsString('R&D segmentation and <firewall> rules.', $body);
        $this->assertStringNotContainsString('&amp;', $body);
        $this->assertStringNotContainsString('&lt;', $body);
    }

    public function test_sitemap_and_robots_are_public(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
    }

    /**
     * Resolve a raw INQUIRY_RECIPIENT value through config/tricetech.php exactly
     * as the running application does.
     *
     * @return array<int, string>
     */
    private static function recipientsFor(string $value): array
    {
        $previous = ['env' => $_ENV['INQUIRY_RECIPIENT'] ?? null, 'server' => $_SERVER['INQUIRY_RECIPIENT'] ?? null];

        $_ENV['INQUIRY_RECIPIENT'] = $value;
        $_SERVER['INQUIRY_RECIPIENT'] = $value;
        putenv("INQUIRY_RECIPIENT={$value}");

        try {
            /** @var array{inquiry_recipients: array<int, string>} $config */
            $config = require config_path('tricetech.php');

            return $config['inquiry_recipients'];
        } finally {
            unset($_ENV['INQUIRY_RECIPIENT'], $_SERVER['INQUIRY_RECIPIENT']);
            putenv('INQUIRY_RECIPIENT');

            if ($previous['env'] !== null) {
                $_ENV['INQUIRY_RECIPIENT'] = $previous['env'];
            }

            if ($previous['server'] !== null) {
                $_SERVER['INQUIRY_RECIPIENT'] = $previous['server'];
            }
        }
    }
}
