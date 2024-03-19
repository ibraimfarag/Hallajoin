<div id="appid" style="margin-top: 40px; margin-bottom: 40px">
    <!-- Use vuedraggable component to enable dragging and dropping of sections -->
    <draggable v-model="sections" group="sections" @start="dragging = true" @end="dragging = false" class="dragArea">
        <div v-for="(section, index) in sections" :key="index" class="form-group-item" style="margin-top: 40px; margin-bottom: 40px">
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-1">
                        <i class="fa fa-arrows-alt move-icon" style="cursor: move"></i>
                    </div>
                    <div class="col-md-10 text-left">
                        <input type="text" class="form-control include-title" v-model="section.title" placeholder="{{__('Example Section title')}}" />
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm" @click="removeSection(index)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="g-items">
                <div class="item" v-for="(item, itemIndex) in section.items" :key="itemIndex" :data-number="itemIndex">
                    <div class="row">
                        <div class="col-md-11">
                            <input type="text" v-model="item.title" class="form-control" placeholder="{{__('Eg: Specialized bilingual guide')}}">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm btn-remove-item" @click="removeItem(index, itemIndex)"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-info btn-sm btn-add-item" @click="addItem(index)"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</button>
            </div>
        </div>
    </draggable>
    <div class="text-center mt-3">
        <button type="button" class="btn btn-success" @click="addSection">Add Section</button>
    </div>
    <input type="hidden" name="sections" :value="formatData(sections)">
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.23.2/vuedraggable.umd.min.js"></script>
<script>
    new Vue({
        el: '#appid',
        data: {
            sections: {!! $translation->sections ?? '[]' !!}, // Load sections from JSON data
            dragging: false // Variable to track dragging state
        },
        methods: {
            addItem(sectionIndex) {
                this.sections[sectionIndex].items.push({ title: '' });
            },
            removeItem(sectionIndex, itemIndex) {
                this.sections[sectionIndex].items.splice(itemIndex, 1);
            },
            addSection() {
                this.sections.push({
                    title: '',
                    items: []
                });
            },
            removeSection(sectionIndex) {
                this.sections.splice(sectionIndex, 1);
            },
            formatData(sections) {
                return JSON.stringify(sections);
            }
        },
        computed: {
            sortedSections() {
                // Sort sections by their order in the array
                return this.sections.slice().sort((a, b) => this.sections.indexOf(a) - this.sections.indexOf(b));
            }
        }
    });
</script>
