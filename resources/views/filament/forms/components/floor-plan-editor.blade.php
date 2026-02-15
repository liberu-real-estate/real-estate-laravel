<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="floorPlanEditor({
        state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }},
    })" class="floor-plan-editor">
        <div class="space-y-4">
            <!-- Floor Plan Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Floor Plan Image
                </label>
                <input 
                    type="file" 
                    @change="handleImageUpload($event)"
                    accept="image/*"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Upload a floor plan image (PNG, JPG, or SVG)
                </p>
            </div>

            <!-- Canvas Container -->
            <div x-show="imageLoaded" class="relative border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                <canvas 
                    x-ref="canvas"
                    class="w-full"
                    @click="handleCanvasClick($event)"
                ></canvas>
            </div>

            <!-- Tools -->
            <div x-show="imageLoaded" class="flex gap-2 flex-wrap">
                <button 
                    type="button"
                    @click="setTool('room')"
                    :class="currentTool === 'room' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-4 py-2 rounded-lg font-medium hover:opacity-80 transition"
                >
                    Add Room
                </button>
                <button 
                    type="button"
                    @click="setTool('marker')"
                    :class="currentTool === 'marker' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-4 py-2 rounded-lg font-medium hover:opacity-80 transition"
                >
                    Add Marker
                </button>
                <button 
                    type="button"
                    @click="clearAnnotations()"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition"
                >
                    Clear All
                </button>
            </div>

            <!-- Annotation List -->
            <div x-show="annotations.length > 0" class="space-y-2">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Annotations:</h4>
                <template x-for="(annotation, index) in annotations" :key="index">
                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium" x-text="annotation.type === 'room' ? '🏠' : '📍'"></span>
                            <input 
                                type="text" 
                                x-model="annotation.label"
                                @input="updateState()"
                                placeholder="Label"
                                class="text-sm border-gray-300 dark:border-gray-600 rounded px-2 py-1"
                            />
                        </div>
                        <button 
                            type="button"
                            @click="removeAnnotation(index)"
                            class="text-red-500 hover:text-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function floorPlanEditor(config) {
            return {
                state: config.state,
                imageLoaded: false,
                currentTool: 'room',
                annotations: [],
                floorPlanImage: null,
                canvas: null,
                ctx: null,
                
                init() {
                    this.canvas = this.$refs.canvas;
                    this.ctx = this.canvas.getContext('2d');
                    
                    // Load existing state if available
                    if (this.state && typeof this.state === 'object') {
                        if (this.state.image) {
                            this.loadImage(this.state.image);
                        }
                        if (this.state.annotations) {
                            this.annotations = this.state.annotations || [];
                        }
                    }
                },
                
                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.loadImage(e.target.result);
                    };
                    reader.readAsDataURL(file);
                },
                
                loadImage(imageSrc) {
                    const img = new Image();
                    img.onload = () => {
                        this.floorPlanImage = img;
                        this.canvas.width = img.width;
                        this.canvas.height = img.height;
                        this.imageLoaded = true;
                        this.redraw();
                        this.updateState();
                    };
                    img.src = imageSrc;
                },
                
                setTool(tool) {
                    this.currentTool = tool;
                },
                
                handleCanvasClick(event) {
                    if (!this.imageLoaded) return;
                    
                    const rect = this.canvas.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    const y = event.clientY - rect.top;
                    
                    const annotation = {
                        type: this.currentTool,
                        x: x,
                        y: y,
                        label: this.currentTool === 'room' ? 'Room' : 'Point of Interest'
                    };
                    
                    this.annotations.push(annotation);
                    this.redraw();
                    this.updateState();
                },
                
                removeAnnotation(index) {
                    this.annotations.splice(index, 1);
                    this.redraw();
                    this.updateState();
                },
                
                clearAnnotations() {
                    this.annotations = [];
                    this.redraw();
                    this.updateState();
                },
                
                redraw() {
                    if (!this.ctx || !this.floorPlanImage) return;
                    
                    // Clear canvas
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    
                    // Draw floor plan image
                    this.ctx.drawImage(this.floorPlanImage, 0, 0);
                    
                    // Draw annotations
                    this.annotations.forEach((annotation, index) => {
                        if (annotation.type === 'room') {
                            // Draw room marker (circle)
                            this.ctx.fillStyle = 'rgba(59, 130, 246, 0.7)';
                            this.ctx.beginPath();
                            this.ctx.arc(annotation.x, annotation.y, 20, 0, 2 * Math.PI);
                            this.ctx.fill();
                            this.ctx.strokeStyle = '#1e40af';
                            this.ctx.lineWidth = 2;
                            this.ctx.stroke();
                        } else {
                            // Draw marker (pin)
                            this.ctx.fillStyle = 'rgba(239, 68, 68, 0.7)';
                            this.ctx.beginPath();
                            this.ctx.arc(annotation.x, annotation.y, 15, 0, 2 * Math.PI);
                            this.ctx.fill();
                            this.ctx.strokeStyle = '#991b1b';
                            this.ctx.lineWidth = 2;
                            this.ctx.stroke();
                        }
                        
                        // Draw label
                        this.ctx.fillStyle = '#000';
                        this.ctx.font = '12px Arial';
                        this.ctx.fillText(annotation.label, annotation.x + 25, annotation.y + 5);
                    });
                },
                
                updateState() {
                    this.state = {
                        image: this.floorPlanImage ? this.floorPlanImage.src : null,
                        annotations: this.annotations
                    };
                }
            }
        }
    </script>
    @endpush
</x-dynamic-component>
