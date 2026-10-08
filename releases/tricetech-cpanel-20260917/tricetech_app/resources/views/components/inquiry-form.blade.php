<section class="band" id="discussion" aria-labelledby="discussion-title">
    <div class="shell discussion__grid">
        <div class="discussion-copy">
            <p class="eyebrow">Architecture discussion</p>
            <h2 class="h2" id="discussion-title">Start an architecture discussion.</h2>
            <p class="lede">Outline your environment, migration plans or upcoming deployment. Our engineers can help define a practical next step — and tell you when the current environment is already the right answer.</p>

            <div class="contact-card">
                <div class="contact-card__row">
                    <span class="contact-card__key">Telephone</span>
                    <span class="contact-card__val"><a href="tel:+19056246400">+1 905-624-6400</a></span>
                </div>
                <div class="contact-card__row">
                    <span class="contact-card__key">Email</span>
                    <span class="contact-card__val"><a href="mailto:info@tricetech.ca">info@tricetech.ca</a></span>
                </div>
                <div class="contact-card__row">
                    <span class="contact-card__key">Office</span>
                    <span class="contact-card__val">Suite 5, 5160 Explorer Drive, Mississauga, ON L4W 4T7</span>
                </div>
                <div class="contact-card__row">
                    <span class="contact-card__key">Response</span>
                    <span class="contact-card__val">Reviewed by our engineering team</span>
                </div>
            </div>
        </div>

        <div class="form-panel">
            @if (session('inquiry_success'))
                <div class="notice notice-success" role="status" tabindex="-1" data-focus-notice>
                    <strong>Inquiry received</strong>
                    <p>{{ session('inquiry_success') }} Our team will review the details you provided.</p>
                </div>
            @endif
            @if (session('inquiry_error'))
                <div class="notice notice-error" role="alert" tabindex="-1" data-focus-notice>
                    <strong>We could not send your request</strong>
                    <p>{{ session('inquiry_error') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="notice notice-error" role="alert" tabindex="-1" data-focus-notice>
                    <strong>Please correct the highlighted fields.</strong>
                    <p>Your other information has been kept.</p>
                </div>
            @endif

            <form method="post" action="{{ route('inquiries.store') }}" data-inquiry-form>
                @csrf
                <div class="field visually-hidden" aria-hidden="true">
                    <label for="company_website">Company website</label>
                    <input id="company_website" name="company_website" type="text" tabindex="-1" autocomplete="off">
                </div>
                <div class="field">
                    <label for="email">Corporate email address <span aria-hidden="true">*</span></label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="254" autocomplete="email" required aria-describedby="email-help @error('email') email-error @enderror" @error('email') aria-invalid="true" @enderror>
                    <span class="field-help" id="email-help">We use this address only to respond to this request.</span>
                    @error('email')<span class="field-error" id="email-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="interest">Service interest <span>(optional)</span></label>
                    <select id="interest" name="interest" @error('interest') aria-invalid="true" aria-describedby="interest-error" @enderror>
                        <option value="">Choose an area</option>
                        @foreach (\App\Http\Requests\StoreInquiryRequest::INTERESTS as $interest)
                            <option value="{{ $interest }}" @selected(old('interest') === $interest)>{{ $interest }}</option>
                        @endforeach
                    </select>
                    @error('interest')<span class="field-error" id="interest-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="message">Technical description <span aria-hidden="true">*</span></label>
                    <textarea id="message" name="message" rows="7" minlength="10" maxlength="2000" required aria-describedby="message-help message-count @error('message') message-error @enderror">{{ old('message') }}</textarea>
                    <div class="field-meta"><span id="message-help">Do not include passwords or access credentials.</span><span id="message-count" data-character-count>0 / 2,000</span></div>
                    @error('message')<span class="field-error" id="message-error">{{ $message }}</span>@enderror
                </div>
                <p class="privacy-note">By submitting, you ask Tricetech Solutions to use these details to respond to your inquiry. <a href="{{ route('privacy') }}">Read the privacy notice.</a></p>
                <button class="button submit-button" type="submit" data-submit-button><span>Send architecture request</span></button>
            </form>
        </div>
    </div>
</section>
