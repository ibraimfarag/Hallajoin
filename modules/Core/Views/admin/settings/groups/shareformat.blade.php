<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("WhatsApp")}}</h3>
        <p class="form-group-desc">{{__('Information of your activity for customer ')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
 
                <div class="form-group">
                    <label>{{__("whatsapp content")}}</label>
                    <div class="form-controls">
                        <textarea name="whatsapp_fromat" class="form-control" cols="30" rows="7">{{setting_item_with_lang('whatsapp_fromat',request()->query('lang'))}}</textarea>
                    </div>
                </div>

            
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Mail")}}</h3>
        <p class="form-group-desc">{{__('Information of your activity for customer')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
 
                <div class="form-group">
                    <label>{{__("Mail content")}}</label>
                    <div class="form-controls">
                        <textarea name="site_desc" class="form-control" cols="30" rows="7">{{setting_item_with_lang('site_desc',request()->query('lang'))}}</textarea>
                    </div>
                </div>

            
            </div>
        </div>
    </div>
</div>


{{-- @push('js')
    <script src="{{asset('libs/ace/src-min-noconflict/ace.js')}}" type="text/javascript" charset="utf-8"></script>
    <script>
        (function ($) {
            $('.ace-editor').each(function () {
                var editor = ace.edit($(this).attr('id'));
                editor.setTheme("ace/theme/"+$(this).data('theme'));
                editor.session.setMode("ace/mode/"+$(this).data('mod'));
                var me = $(this);

                editor.session.on('change', function(delta) {
                    // delta.start, delta.end, delta.lines, delta.action
                    me.next('textarea').val(editor.getValue());
                });
            });
        })(jQuery)
    </script>
@endpush --}}
