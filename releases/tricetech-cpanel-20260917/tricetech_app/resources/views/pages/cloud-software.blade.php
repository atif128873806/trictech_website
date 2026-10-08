@extends('layouts.app')

@section('title', 'Cloud & Software | Tricetech Solutions')
@section('description', 'Managed cloud infrastructure, private storage, Oracle APEX applications, enterprise messaging and application migration services.')

@section('content')
<section class="page-hero">
    <div class="shell page-hero__grid">
        <div>
            <p class="eyebrow">Service family 02</p>
            <h1 class="display">Cloud capacity and software shaped around the work.</h1>
            <p class="lede">Tricetech brings managed compute, private storage, Oracle APEX applications and migration planning together as one practical architecture — sized for production, operated by the same team that built it.</p>
            <div class="page-hero__meta">
                <div><b>04</b>Published service areas</div>
                <div><b>Block · Object</b>Archival storage tiers</div>
                <div><b>Zero data loss</b>Published cutover parameters</div>
            </div>
        </div>
        <figure class="figure figure--dark reveal">
            <div class="figure__frame">
                <x-schematic.stack />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 03</b> — Managed service stack with per-layer controls</span>
                <span class="figure__id">STACK-01</span>
            </figcaption>
        </figure>
    </div>
</section>

<section class="band" aria-labelledby="cloud-services">
    <div class="shell">
        <x-sheet-index code="SEC 01" title="Published catalogue" />
        <x-section-head
            eyebrow="Published catalogue"
            title="Cloud & software services"
            note="Four areas that are usually bought separately and operated badly. Here they share one architecture, one monitoring layer and one point of accountability."
            id="cloud-services"
        />

        <figure class="figure figure--offset reveal">
            <div class="figure__frame">
                <x-schematic.servicemap reference="MAP-02" :columns="2" :items="[
                    ['IaaS & Managed VPS', 'Compute · storage · networking'],
                    ['Private Cloud Storage', 'Access · backup · versioning'],
                    ['Business Applications & Oracle APEX', 'Applications · dashboards'],
                    ['Enterprise Messaging & Migration', 'Messaging · cutovers'],
                ]" />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 08</b> — The four published service areas of this family</span>
                <span class="figure__id">MAP-02</span>
            </figcaption>
        </figure>

        <div class="services" data-stagger>
            <article class="service-row service-row--large reveal">
                <span class="service-row__index">01</span>
                <h3 class="service-row__title">IaaS &amp; Managed VPS</h3>
                <div class="service-row__body">
                    <p>Elastic production compute and storage for databases, web services and staging environments across Windows and Linux, on infrastructure sized for the workload rather than a fixed package.</p>
                    <ul class="chips">
                        <li>Virtual data centre</li><li>Block, object and archival storage</li><li>Custom networking</li><li>Load balancing</li><li>High-availability failover</li>
                    </ul>
                </div>
            </article>
            <article class="service-row service-row--large reveal">
                <span class="service-row__index">02</span>
                <h3 class="service-row__title">Private Cloud Storage</h3>
                <div class="service-row__body">
                    <p>Dedicated centralized repositories that keep access, recovery and sharing under clear operational control, with the asset held privately rather than pooled with other tenants.</p>
                    <ul class="chips">
                        <li>Role-based access</li><li>Endpoint encryption</li><li>Cross-volume backup</li><li>Immutable versioning</li><li>Time-limited links</li><li>Audit logging</li>
                    </ul>
                </div>
            </article>
            <article class="service-row service-row--large reveal">
                <span class="service-row__index">03</span>
                <h3 class="service-row__title">Business Applications &amp; Oracle APEX ERP</h3>
                <div class="service-row__body">
                    <p>Custom, scalable applications and process automation that bring finance, human resources and inventory information into useful dashboards — built on Oracle APEX so the business keeps a system it can extend.</p>
                    <ul class="chips">
                        <li>Oracle APEX</li><li>ERP workflows</li><li>Operational dashboards</li><li>Low-code automation</li>
                    </ul>
                </div>
            </article>
            <article class="service-row service-row--large reveal">
                <span class="service-row__index">04</span>
                <h3 class="service-row__title">Enterprise Messaging &amp; Application Migration</h3>
                <div class="service-row__body">
                    <p>Domain messaging, transactional SMTP and planned cutovers for applications, databases and virtual machines with minimal downtime and the published zero-data-loss parameters.</p>
                    <ul class="chips">
                        <li>Domain messaging hosting</li><li>Transactional SMTP</li><li>Backup retention</li><li>MySQL</li><li>SQL Server</li><li>PostgreSQL</li><li>Virtual machines</li>
                    </ul>
                </div>
            </article>
        </div>
    </div>
</section>

{{-- Surface rather than paper: the closing band below is paper, and two paper
     bands in a row would read as one block with the call to action inside it. --}}
<section class="band band--surface">
    <div class="shell method method--stack">
        <div class="method__intro">
            <p class="eyebrow">Migration</p>
            <h2 class="h2">Cutovers that are rehearsed before they are performed.</h2>
            <div class="prose">
                <p>The risk in a migration is rarely the new platform. It is the sequencing, the data that moved twice, and the rollback nobody tested. Every cloud engagement sets those parameters in writing before a change window is booked.</p>
            </div>
            <p class="spec-line"><b>Applies to</b><span>Applications · databases · virtual machines</span></p>
        </div>
        <figure class="figure reveal">
            <div class="figure__frame">
                <x-schematic.timeline reference="CUT-01" :stages="[
                    ['code' => '01 / PLAN', 'name' => 'Plan', 'detail' => ['Dependencies, data,', 'acceptance criteria'], 'output' => 'AGREED SCOPE'],
                    ['code' => '02 / REHEARSE', 'name' => 'Rehearse', 'detail' => ['Rehearsed on a copy', 'so timing is known'], 'output' => 'MEASURED DOWNTIME'],
                    ['code' => '03 / CUT OVER', 'name' => 'Cut over', 'detail' => ['Booked window, agreed', 'rollback points'], 'output' => 'ROLLBACK READY'],
                    ['code' => '04 / VERIFY', 'name' => 'Verify', 'detail' => ['Application, backup', 'integration checks'], 'output' => 'RESTORE TESTED'],
                ]" />
            </div>
            <figcaption class="figure__caption">
                <span><b>Figure 09</b> — Migration sequence, from agreed scope to a tested restore</span>
                <span class="figure__id">CUT-01</span>
            </figcaption>
        </figure>
    </div>
</section>

<section class="closing">
    <div class="shell closing__grid">
        <div>
            <p class="eyebrow">Next step</p>
            <h2 class="h2">Plan capacity, migration and application work together.</h2>
        </div>
        <div>
            <p>Bring us the current environment and the outcome you need. We will help define the technical next step.</p>
            <a class="button" href="{{ route('home') }}#discussion">Discuss a cloud or software project</a>
            <p class="closing__alt">Prefer to talk it through? <a href="tel:+19056246400">+1 905-624-6400</a> · <a href="mailto:info@tricetech.ca">info@tricetech.ca</a></p>
        </div>
    </div>
</section>
@endsection
