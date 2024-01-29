<!-- Blade file: add_multiple_sections.blade.php -->

<div id="sections-container">
    <!-- Section Template -->
    <div class="section-template hide">
        <div class="form-group-item">
            <!-- Section Title Input -->
            <label class="control-label">{{__('Section Title')}}</label>
            <input type="text" class="form-control section-title" placeholder="{{__('Enter Section Title')}}">
            
            <!-- Section Items -->
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-11 text-left">{{__("Item Title")}}</div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="g-items">
                <!-- Existing Items -->
                <div class="item" data-number="__number__">
                    <div class="row">
                        <div class="col-md-11">
                            <input type="text" __name__="items[__number__][title]" class="form-control item-title" placeholder="{{__('Eg: Specialized item')}}">
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Add Item Button -->
            <div class="text-right">
                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add Item')}}</span>
            </div>
            
            <!-- Template for Adding New Items (Hidden by Default) -->
            <div class="g-more hide">
                <div class="item" data-number="__number__">
                    <div class="row">
                        <div class="col-md-11">
                            <input type="text" __name__="items[__number__][title]" class="form-control item-title" placeholder="{{__('Eg: Specialized item')}}">
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Button to Add New Section -->
<div class="text-center">
    <button id="btn-add-section" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> {{__('Add Section')}}</button>
</div>
