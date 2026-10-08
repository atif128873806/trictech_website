@extends('layouts.app')

@section('title', 'Tricetech Solutions | Enterprise IT for Canadian Business')
@section('description', 'Tricetech Solutions designs, deploys and supports enterprise infrastructure, cloud, cybersecurity and software environments for Canadian organizations.')

@section('content')
<section class="hero">
    <div class="shell hero__grid">
        <div class="hero__copy">
            <p class="eyebrow">Enterprise IT · Canada</p>
            <h1 class="display">Secure infrastructure.<br>Practical cloud.<br>Software built around your work.</h1>
            <p class="lede">Tricetech Solutions designs, deploys and supports enterprise IT environments for growing mid-market companies and established enterprises across Canada — networks and protection, cloud compute and private storage, and the applications the business actually runs on.</p>
            <div class="hero__actions">
                <a class="button" href="#discussion">Request a discussion</a>
                <a class="text-link" href="#services">Explore services <span aria-hidden="true">→</span></a>
            </div>
            <div class="hero__facts">
                <div>
                    <span class="hero__fact-key">Registered office</span>
                    <span class="hero__fact-value">Mississauga, Ontario</span>
                </div>
                <div>
                    <span class="hero__fact-key">Service area</span>
                    <span class="hero__fact-value">Organizations nationwide</span>
                </div>
            </div>
        </div>
        <figure class="figure figure--dark reveal">
            <div class="figure__frame">
                <x-schematic.topology />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 01</b> — Two-site reference environment with a managed edge</span>
                <span class="figure__id">TOP-01</span>
            </figcaption>
        </figure>
    </div>
</section>

<section class="claims-wrap" aria-label="Published service commitments">
    <div class="shell">
        <div class="claims" data-stagger>
            <div class="claim reveal">
                <span class="claim__value">Enterprise SLA backed</span>
                <span class="claim__label">Commercial service commitments behind every managed environment.</span>
            </div>
            <div class="claim reveal">
                <span class="claim__value">24/7 monitoring</span>
                <span class="claim__label">Continuous surveillance, predictive fault discovery and scheduled patching.</span>
            </div>
            <div class="claim reveal">
                <span class="claim__value">Zero data loss</span>
                <span class="claim__label">Published parameters for application, database and virtual machine cutovers.</span>
            </div>
            <div class="claim reveal">
                <span class="claim__value">No vendor lock-in</span>
                <span class="claim__label">Architecture and platform choices made for the environment, not for a licence.</span>
            </div>
            <div class="claim reveal">
                <span class="claim__value">Sovereign asset control</span>
                <span class="claim__label">Private storage and infrastructure that stay under client ownership.</span>
            </div>
        </div>

        {{-- The claims state what is published. This drawing states how it is
             operated, which a list of commitments cannot show. --}}
        <figure class="figure claims-figure reveal">
            <div class="figure__frame">
                <x-schematic.operations />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 10</b> — How the published service level is operated</span>
                <span class="figure__id">OPS-01</span>
            </figcaption>
        </figure>
    </div>
</section>

<section class="band">
    <div class="shell">
        <x-sheet-index code="SEC 02" title="Company" />
        <x-section-head
            eyebrow="Company"
            title="Built around your business. Supported for the long term."
            note="Tricetech Solutions is a Canadian IT services firm. Engagements begin with the business need and the environment as it actually runs today — then move to a solution that can be operated and paid for over time."
            id="company"
        />

        <div class="overview__grid">
            <div class="overview__statement">
                <p class="lede">We work with organizations that have outgrown ad-hoc IT: multi-site networks, Microsoft and Linux estates, workloads moving between on-premises and cloud.</p>
                <div class="pillars">
                    <div><span class="pillar__value">No vendor lock-in</span><span class="pillar__label">Where the current environment can be improved instead of replaced, we say so.</span></div>
                    <div><span class="pillar__value">Support is designed in</span><span class="pillar__label">The team that architects the environment also runs the monitoring and remediation.</span></div>
                </div>
            </div>

            <div id="capability">
                <p class="eyebrow eyebrow--plain">Technology in operation</p>

                {{-- Drawn rather than tabulated: a text table of ruled rows read
                     as five unrelated lists, when the point is that the domains
                     are layers of one operating estate. Same five domains and the
                     same published technologies, as plates on a schedule. --}}
                <figure class="figure reveal">
                    <div class="figure__frame">
                        <x-schematic.techplate :rows="[
                            ['Cloud & virtualization', 'KVM · CEPH · HYPER-V'],
                            ['Platforms & applications', 'RED HAT LINUX · WINDOWS SERVER · ORACLE APEX · MYSQL'],
                            ['Security operations', 'CROWDSTRIKE · SOPHOS · IMUNIFY360 · MICROSOFT DEFENDER · IDS/IPS'],
                            ['Identity & access', 'ACTIVE DIRECTORY · GROUP POLICY · ENTRA ID · MFA · ZERO TRUST · VPN'],
                            ['Edge & messaging', 'SD-WAN · SPF · DKIM · DMARC · UNIFIED COMMUNICATIONS'],
                        ]" />
                    </div>
                    <figcaption class="figure__caption">
                        <span><b>Figure 11</b> — The technology stack, by domain</span>
                        <span class="figure__id">TECH-01</span>
                    </figcaption>
                </figure>

                <p class="note">Named products appear because they are in production in client environments. No partnership, certification or reseller status is implied.</p>
            </div>
        </div>
    </div>
</section>

<section class="band band--paper" id="services">
    <div class="shell">
        <x-sheet-index code="SEC 03" title="Service families" />
        <x-section-head
            eyebrow="Services"
            title="Services for critical environments"
            note="Two delivery families. Every service area published on the original company site is described in full on the page behind it."
            id="services-title"
        />

        <a class="pathway" href="{{ route('infrastructure') }}">
            <span class="pathway__index">01</span>
            <span class="pathway__title">Infrastructure &amp; Networks</span>
            <p class="pathway__summary">Networks, Microsoft systems, monitoring, communications, server protection and email edge filtering.<br><span class="pathway__meta">09 PUBLISHED SERVICE AREAS</span></p>
            <span class="pathway__mark"><x-schematic.mark variant="network" /></span>
            <span class="pathway__arrow" aria-hidden="true">→</span>
        </a>
        <a class="pathway" href="{{ route('cloud-software') }}">
            <span class="pathway__index">02</span>
            <span class="pathway__title">Cloud &amp; Software</span>
            <p class="pathway__summary">Managed compute and private storage, Oracle APEX business applications, messaging hosting and planned migration.<br><span class="pathway__meta">04 PUBLISHED SERVICE AREAS</span></p>
            <span class="pathway__mark"><x-schematic.mark variant="cloud" /></span>
            <span class="pathway__arrow" aria-hidden="true">→</span>
        </a>
    </div>
</section>

<section class="band band--dark">
    <div class="shell">
        <x-sheet-index code="SEC 04" title="Engagement method" />
        <x-section-head
            eyebrow="How we work"
            title="Understand the business, design the right solution, support it long term."
            note="The same four phases run on every engagement, whether it starts with a network review, a cloud migration or an application that has to be rebuilt."
        />
        <figure class="figure figure--dark figure--offset reveal">
            <div class="figure__frame">
                <x-schematic.timeline reference="ENG-01" :stages="[
                    ['code' => '01 / ASSESS', 'name' => 'Read', 'detail' => ['Routing, data flow,', 'live systems, controls'], 'output' => 'FINDINGS'],
                    ['code' => '02 / DESIGN', 'name' => 'Set', 'detail' => ['Target architecture,', 'a roadmap in order'], 'output' => 'ROADMAP'],
                    ['code' => '03 / BUILD', 'name' => 'Change', 'detail' => ['Staged deployment,', 'agreed rollback points'], 'output' => 'CHANGE WINDOW'],
                    ['code' => '04 / OPERATE', 'name' => 'Run', 'detail' => ['Monitoring, patching,', 'fault remediation'], 'output' => 'RUN STATE'],
                ]" />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 04</b> — Engagement sequence, and what each stage produces</span>
                <span class="figure__id">ENG-01</span>
            </figcaption>
        </figure>

        <div class="phases" data-stagger>
            <article class="phase reveal">
                <span class="phase__step">PHASE 01 — ASSESS</span>
                <h3 class="phase__title">Read the environment</h3>
                <p class="phase__body">Findings written down, including what already works.</p>
            </article>
            <article class="phase reveal">
                <span class="phase__step">PHASE 02 — DESIGN</span>
                <h3 class="phase__title">Set the target</h3>
                <p class="phase__body">A roadmap with spending and compliance in view.</p>
            </article>
            <article class="phase reveal">
                <span class="phase__step">PHASE 03 — BUILD</span>
                <h3 class="phase__title">Change in stages</h3>
                <p class="phase__body">Rollback points agreed before the window opens.</p>
            </article>
            <article class="phase reveal">
                <span class="phase__step">PHASE 04 — OPERATE</span>
                <h3 class="phase__title">Run and improve</h3>
                <p class="phase__body">Monitoring, patching and remediation, continuously.</p>
            </article>
        </div>
    </div>
</section>

<section class="band">
    <div class="shell">
        <x-sheet-index code="SEC 05" title="Environment coverage" />
        <x-section-head
            eyebrow="Coverage"
            title="What a managed environment covers"
            note="Three layers, drawn from the published service catalogue. Most engagements begin in one and grow into the others."
        />
        <figure class="figure reveal">
            <div class="figure__frame">
                <x-schematic.coverage />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 05</b> — Three coverage layers and the controls operated at each</span>
                <span class="figure__id">COV-01</span>
            </figcaption>
        </figure>

        <p class="spec-line"><b>Typical entry point</b><span>Most engagements start at one layer and grow into the others.</span></p>
    </div>
</section>

<x-inquiry-form />
@endsection
