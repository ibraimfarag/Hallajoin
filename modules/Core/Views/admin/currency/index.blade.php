@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">{{__('Currency Settings')}}</h1>
        </div>

        <div class="row">
            <!-- Current Currency Info -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{__('Current Currency')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{__('Currency Code')}}</label>
                            <input type="text" class="form-control" value="{{ strtoupper($currentCurrency) }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{__('Current Symbol')}}</label>
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control me-2" value="{{ currency_symbol() }}" readonly>
                                <span class="badge bg-secondary">Text</span>
                            </div>
                        </div>

                        @if(!empty($currentSvgSymbol))
                            <div class="mb-3">
                                <label class="form-label">{{__('Current SVG Symbol')}}</label>
                                <div class="d-flex align-items-center">
                                    <div class="me-2"
                                        style="min-width: 30px; height: 30px; display: flex; align-items: center;">
                                        {!! $currentSvgSymbol !!}
                                    </div>
                                    <span class="badge bg-success">SVG</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SVG Symbol Settings -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{__('SVG Symbol Settings')}}</h5>
                    </div>
                    <div class="card-body">
                        <form id="svgSymbolForm">
                            <div class="mb-3">
                                <label for="currency_symbol_svg" class="form-label">{{__('SVG Symbol Code')}}</label>
                                <textarea class="form-control" id="currency_symbol_svg" name="currency_symbol_svg" rows="8"
                                    placeholder="Paste your SVG code here...">{{ $currentSvgSymbol }}</textarea>
                                <div class="d-flex justify-content-between">
                                    <div class="form-text">
                                        {{__('Paste the complete SVG code for your currency symbol (max 5000 characters)')}}
                                    </div>
                                    <small class="text-muted" id="charCounter">0 / 5000</small>
                                </div>
                                <div class="alert alert-info mt-2">
                                    <small><i class="fa fa-info-circle"></i>
                                        {{__('Note: After saving, the changes will take effect immediately. The system automatically clears cache to ensure updates appear.')}}</small>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">{{__('Save SVG Symbol')}}</button>
                                <button type="button" class="btn btn-secondary" id="previewBtn">{{__('Preview')}}</button>
                                <button type="button" class="btn btn-outline-danger" id="clearBtn">{{__('Clear')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency-Specific SVG Management -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{__('Manage SVG for Each Currency')}}</h5>
                        <small class="text-muted">{{__('Set custom SVG symbols for individual currencies')}}</small>
                    </div>
                    <div class="card-body">
                        @foreach($activeCurrencies as $currency)
                            @php
                                $currencyCode = strtolower($currency['currency_main']);
                                $currentSvg = $currencySvgs[$currencyCode] ?? '';
                            @endphp
                            <div class="row mb-4 p-3 border rounded">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h6 class="mb-2">{{ strtoupper($currency['currency_main']) }}</h6>
                                        <div class="mb-2">
                                            @if(!empty($currentSvg))
                                                <div style="font-size: 24px;">{!! $currentSvg !!}</div>
                                                <small class="text-success">{{__('SVG Active')}}</small>
                                            @else
                                                <div style="font-size: 24px;">{{ \App\Currency::getCurrency($currency['currency_main'])['symbol'] ?? '$' }}</div>
                                                <small class="text-muted">{{__('Text Symbol')}}</small>
                                            @endif
                                        </div>
                                        <span class="badge {{ $currency['currency_main'] == $currentCurrency ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ $currency['currency_main'] == $currentCurrency ? __('Current') : __('Alternative') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <form class="currency-svg-form" data-currency="{{ $currencyCode }}">
                                        <div class="mb-3">
                                            <label class="form-label">{{__('SVG Code for')}} {{ strtoupper($currency['currency_main']) }}</label>
                                            <textarea 
                                                class="form-control currency-svg-input" 
                                                rows="3"
                                                placeholder="{{__('Paste SVG code here...')}}"
                                                maxlength="5000">{{ $currentSvg }}</textarea>
                                            <div class="form-text">{{__('SVG symbols will override text symbols when available')}}</div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">{{__('Save')}}</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm preview-currency-btn" 
                                                    data-currency="{{ $currencyCode }}">{{__('Preview')}}</button>
                                            <button type="button" class="btn btn-outline-danger btn-sm clear-currency-btn"
                                                    data-currency="{{ $currencyCode }}">{{__('Clear')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{__('Preview')}}</h5>
                    </div>
                    <div class="card-body">
                        <div id="previewSection" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>{{__('With SVG Symbol')}}</h6>
                                    <div class="p-3 bg-light rounded">
                                        <span id="previewWithSvg" class="fs-4"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>{{__('With Text Symbol')}}</h6>
                                    <div class="p-3 bg-light rounded">
                                        <span id="previewWithoutSvg" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Examples Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{__('SVG Examples')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6>AED Dirham (Đ)</h6>
                                <div class="mb-2">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15v-2h2v2h-2zm0-4V8h2v5h-2z" />
                                    </svg>
                                </div>
                                <textarea class="form-control" rows="3" readonly>&lt;svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"&gt;
      &lt;path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15v-2h2v2h-2zm0-4V8h2v5h-2z"/&gt;
    &lt;/svg&gt;</textarea>
                            </div>

                            <div class="col-md-4">
                                <h6>USD Dollar ($)</h6>
                                <div class="mb-2">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                                    </svg>
                                </div>
                                <textarea class="form-control" rows="3" readonly>&lt;svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"&gt;
      &lt;path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/&gt;
    &lt;/svg&gt;</textarea>
                            </div>

                            <div class="col-md-4">
                                <h6>EUR Euro (€)</h6>
                                <div class="mb-2">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M15 18.5c-2.51 0-4.68-.81-6.5-2.4h5.5v-2H6.31C6.11 13.46 6 12.74 6 12s.11-1.46.31-2.1H14v-2H8.5C10.32 6.31 12.49 5.5 15 5.5c1.61 0 3.09.59 4.23 1.57L21 5.3C19.41 3.87 17.3 3 15 3c-3.92 0-7.24 2.51-8.48 6H3v2h3.06c-.04.33-.06.66-.06 1s.02.67.06 1H3v2h3.52c1.24 3.49 4.56 6 8.48 6 2.31 0 4.41-.87 6-2.3l-1.77-1.77c-1.13.98-2.6 1.57-4.23 1.57z" />
                                    </svg>
                                </div>
                                <textarea class="form-control" rows="3" readonly>&lt;svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"&gt;
      &lt;path d="M15 18.5c-2.51 0-4.68-.81-6.5-2.4h5.5v-2H6.31C6.11 13.46 6 12.74 6 12s.11-1.46.31-2.1H14v-2H8.5C10.32 6.31 12.49 5.5 15 5.5c1.61 0 3.09.59 4.23 1.57L21 5.3C19.41 3.87 17.3 3 15 3c-3.92 0-7.24 2.51-8.48 6H3v2h3.06c-.04.33-.06.66-.06 1s.02.67.06 1H3v2h3.52c1.24 3.49 4.56 6 8.48 6 2.31 0 4.41-.87 6-2.3l-1.77-1.77c-1.13.98-2.6 1.57-4.23 1.57z"/&gt;
    &lt;/svg&gt;</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('svgSymbolForm');
            const previewBtn = document.getElementById('previewBtn');
            const clearBtn = document.getElementById('clearBtn');
            const textarea = document.getElementById('currency_symbol_svg');
            const previewSection = document.getElementById('previewSection');
            const charCounter = document.getElementById('charCounter');

            // تحديث عداد الأحرف
            function updateCharCounter() {
                const length = textarea.value.length;
                charCounter.textContent = `${length} / 5000`;
                charCounter.className = length > 5000 ? 'text-danger' : 'text-muted';
            }

            // تحديث العداد عند التحميل
            updateCharCounter();

            // تحديث العداد عند الكتابة
            textarea.addEventListener('input', updateCharCounter);

            // حفظ رمز SVG
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const svgContent = textarea.value;

                // التحقق من طول المحتوى
                if (svgContent.length > 5000) {
                    alert('رمز SVG طويل جداً. الحد الأقصى 5000 حرف.');
                    return;
                }

                // التحقق من صحة SVG (اختياري)
                if (svgContent.trim() && !svgContent.toLowerCase().includes('<svg')) {
                    if (!confirm('المحتوى لا يبدو وكأنه رمز SVG صالح. هل تريد المتابعة؟')) {
                        return;
                    }
                }

                const formData = new FormData(form);

                fetch('{{ route('admin.currency.update-svg') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('تم حفظ رمز العملة SVG بنجاح!');
                            location.reload();
                        } else {
                            alert('خطأ: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء الحفظ');
                    });
            });

            // معاينة
            previewBtn.addEventListener('click', function () {
                const svgCode = textarea.value;

                fetch('{{ route('admin.currency.preview') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        svg_symbol: svgCode
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('previewWithSvg').innerHTML = data.with_svg;
                        document.getElementById('previewWithoutSvg').innerHTML = data.without_svg;
                        previewSection.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء المعاينة');
                    });
            });

            // مسح
            clearBtn.addEventListener('click', function () {
                if (confirm('هل أنت متأكد من مسح رمز SVG؟')) {
                    textarea.value = '';
                    previewSection.style.display = 'none';
                    updateCharCounter();
                }
            });
        });

        // التعامل مع نماذج العملات المختلفة
        document.querySelectorAll('.currency-svg-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const currencyCode = this.dataset.currency;
                const svgInput = this.querySelector('.currency-svg-input');
                const svgContent = svgInput.value.trim();
                
                // التحقق من طول المحتوى
                if (svgContent.length > 5000) {
                    alert('رمز SVG طويل جداً. الحد الأقصى 5000 حرف.');
                    return;
                }
                
                // إرسال البيانات
                fetch('{{ route("admin.currency.update-currency-svg") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        currency_code: currencyCode,
                        svg_symbol: svgContent
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // إعادة تحميل الصفحة لعرض التحديثات
                        location.reload();
                    } else {
                        alert('خطأ: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء الحفظ');
                });
            });
        });

        // أزرار المسح للعملات المختلفة
        document.querySelectorAll('.clear-currency-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const currencyCode = this.dataset.currency;
                const form = document.querySelector(`form[data-currency="${currencyCode}"]`);
                const svgInput = form.querySelector('.currency-svg-input');
                
                if (confirm(`هل أنت متأكد من مسح رمز SVG للعملة ${currencyCode.toUpperCase()}؟`)) {
                    svgInput.value = '';
                }
            });
        });
    </script>
@endsection