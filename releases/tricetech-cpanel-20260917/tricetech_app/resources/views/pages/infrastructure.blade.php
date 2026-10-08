@extends('layouts.app')

@section('title', 'Infrastructure & Networks | Tricetech Solutions')
@section('description', 'Network architecture, Microsoft systems, monitoring, communications, server protection, secure access and email security services.')

@section('content')
<section class="page-hero">
    <div class="shell page-hero__grid">
        <div>
            <p class="eyebrow">Service family 01</p>
            <h1 class="display">Infrastructure designed for how your organization operates.</h1>
            <p class="lede">From campus networks and Microsoft systems to secure remote access and continuous monitoring, Tricetech builds and supports dependable environments with a practical path forward.</p>
            <div class="page-hero__meta">
                <div><b>09</b>Published service areas</div>
                <div><b>24/7</b>Monitoring and patching</div>
                <div><b>Multi-site</b>LAN, WAN and SD-WAN</div>
            </div>
        </div>
        <figure class="figure figure--dark reveal">
            <div class="figure__frame">
                <x-schematic.perimeter />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 02</b> — Enforcement points and the monitoring layer beneath them</span>
                <span class="figure__id">SEC-01</span>
            </figcaption>
        </figure>
    </div>
</section>

<section class="band" aria-labelledby="infrastructure-services">
    <div class="shell">
        <x-sheet-index code="SEC 01" title="Published catalogue" />
        <x-section-head
            eyebrow="Published catalogue"
            title="Infrastructure & network services"
            note="Nine areas, each one supported end to end — design, deployment, operation and the remediation that follows an incident."
            id="infrastructure-services"
        />

        @php($services = [
            [
                'Routing &amp; Switching',
                'Design and deployment for LAN and WAN environments, multi-site campuses, enterprise wireless and managed SD-WAN.',
                ['VLAN and routing design for campus and branch', 'Multi-site WAN and managed SD-WAN', 'Enterprise wireless coverage and roaming'],
            ],
            [
                'Network Consulting &amp; Planning',
                'Assessment of live environments and technical roadmaps aligned with business priorities, spending and compliance goals.',
                ['Current-state review of the live environment', 'Sequenced technical roadmap with cost drivers', 'Alignment to compliance and growth plans'],
            ],
            [
                'Audits &amp; Optimization',
                'Inspection of routing and data flow to identify congestion, processing bottlenecks and perimeter anomalies.',
                ['Routing and data-flow inspection', 'Congestion and processing bottlenecks', 'Perimeter anomaly review'],
            ],
            [
                'Microsoft Systems Management',
                'Administration for Active Directory, Group Policy, DNS and DHCP, Hyper-V and Microsoft 365 hybrid environments.',
                ['Active Directory and Group Policy', 'DNS and DHCP services', 'Hyper-V virtualization and Microsoft 365 hybrid'],
            ],
            [
                'Monitoring &amp; Support',
                '24/7 performance surveillance, predictive fault discovery, scheduled patching and onsite remediation.',
                ['Continuous 24/7 surveillance', 'Predictive fault discovery and alerting', 'Scheduled patching and onsite remediation'],
            ],
            [
                'Unified Communications',
                'Video, VoIP, secure internal communications, encrypted sharing and remote collaboration brought into one working environment.',
                ['Video and VoIP platforms', 'Secure internal communications', 'Encrypted file sharing and remote collaboration'],
            ],
            [
                'Unified Server Protection',
                'Protection for physical, virtual and cloud workloads with monitoring and IDS/IPS using CrowdStrike, Sophos and Microsoft Defender.',
                ['Physical, virtual and cloud workload protection', 'Monitoring with IDS/IPS', 'CrowdStrike, Sophos and Microsoft Defender'],
            ],
            [
                'Secure Remote Access &amp; VPN',
                'Encrypted site-to-site connections and user-level Zero Trust access with MFA and Microsoft Entra ID.',
                ['Encrypted site-to-site tunnels', 'User-level Zero Trust access', 'MFA enforced through Microsoft Entra ID'],
            ],
            [
                'Email Edge Protection',
                'Inbound and outbound filtering with SPF, DKIM and DMARC controls to reduce spoofing and phishing risk.',
                ['Inbound and outbound filtering', 'SPF, DKIM and DMARC enforcement', 'Spoofing and phishing reduction'],
            ],
        ])

        <figure class="figure figure--offset reveal">
            <div class="figure__frame">
                <x-schematic.servicemap reference="MAP-01" :items="[
                    ['Routing & Switching', 'LAN · WAN · SD-WAN'],
                    ['Network Consulting & Planning', 'Roadmap · cost drivers'],
                    ['Audits & Optimization', 'Flow · bottleneck'],
                    ['Microsoft Systems Management', 'AD · DNS · Microsoft 365'],
                    ['Monitoring & Support', '24/7 · patching'],
                    ['Unified Communications', 'Video · VoIP · sharing'],
                    ['Unified Server Protection', 'CrowdStrike · Sophos · Defender'],
                    ['Secure Remote Access & VPN', 'Zero Trust · Entra ID'],
                    ['Email Edge Protection', 'SPF · DKIM · DMARC'],
                ]" />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 06</b> — The nine published service areas of this family</span>
                <span class="figure__id">MAP-01</span>
            </figcaption>
        </figure>

        <div class="services" data-stagger>
            @foreach ($services as $index => [$title, $description, $scope])
                <article class="service-row reveal">
                    <span class="service-row__index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="service-row__title">{!! $title !!}</h3>
                    <div class="service-row__body">
                        <p>{{ $description }}</p>
                        <ul class="chips">
                            @foreach ($scope as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="band band--surface">
    <div class="shell method method--stack">
        <div class="method__intro">
            <p class="eyebrow">Architecture review</p>
            <h2 class="h2">Review the environment before the next change.</h2>
            <div class="prose">
                <p>Most infrastructure work starts with an assumption nobody has tested: that the network behaves the way the diagram says it does. A short review replaces that assumption with findings you can act on, in the order that matters.</p>
            </div>
            <p class="spec-line"><b>Typical input</b><span>Existing documentation, topologies and access</span></p>
        </div>
        <figure class="figure reveal">
            <div class="figure__frame">
                <x-schematic.timeline reference="REV-01" :stages="[
                    ['code' => '01 / INVENTORY', 'name' => 'Inventory', 'detail' => ['What exists, what is', 'supported, undocumented'], 'output' => 'RISK ORDER'],
                    ['code' => '02 / INSPECT', 'name' => 'Inspect', 'detail' => ['Routing, congestion,', 'processing bottlenecks'], 'output' => 'OBSERVED, NOT ASSUMED'],
                    ['code' => '03 / REPORT', 'name' => 'Report', 'detail' => ['Findings, priorities,', 'cost drivers attached'], 'output' => 'PLAIN LANGUAGE'],
                    ['code' => '04 / ROADMAP', 'name' => 'Roadmap', 'detail' => ['Sequence aligned to', 'spending and compliance'], 'output' => 'SEQUENCED PLAN'],
                ]" />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 07</b> — The four steps of an architecture review</span>
                <span class="figure__id">REV-01</span>
            </figcaption>
        </figure>
    </div>
</section>

<section class="closing">
    <div class="shell closing__grid">
        <div>
            <p class="eyebrow">Next step</p>
            <h2 class="h2">Review the environment before the next change.</h2>
        </div>
        <div>
            <p>Discuss network growth, aging systems, security controls or an upcoming deployment with our team.</p>
            <a class="button" href="{{ route('home') }}#discussion">Request an infrastructure review</a>
            <p class="closing__alt">Prefer to talk it through? <a href="tel:+19056246400">+1 905-624-6400</a> · <a href="mailto:info@tricetech.ca">info@tricetech.ca</a></p>
        </div>
    </div>
</section>
@endsection
