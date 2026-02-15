@props(['floorPlanData' => null])

@php
    $floorPlanData = $floorPlanData ?? $attributes->get('floor-plan-data');
@endphp

@if($floorPlanData && is_array($floorPlanData) && isset($floorPlanData['image']))
<div class="floor-plan-viewer my-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Interactive Floor Plan</h3>
    
    <div x-data="floorPlanViewer(@js($floorPlanData))" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600 p-4">
        <div class="relative">
            <canvas 
                x-ref="canvas"
                class="w-full border border-gray-200 dark:border-gray-700 rounded"
                @click="handleCanvasClick($event)"
                @mousemove="handleMouseMove($event)"
            ></canvas>
        </div>
        
        <!-- Annotation Details Modal -->
        <div x-show="selectedAnnotation" 
             x-cloak
             class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg"
        >
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-2xl" x-text="selectedAnnotation?.type === 'room' ? '🏠' : '📍'"></span>
                    <span class="ml-2 font-semibold text-gray-900 dark:text-white" x-text="selectedAnnotation?.label"></span>
                </div>
                <button @click="selectedAnnotation = null" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-4 flex gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-blue-500 border-2 border-blue-800"></div>
                <span class="text-sm text-gray-700 dark:text-gray-300">Room</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-red-500 border-2 border-red-800"></div>
                <span class="text-sm text-gray-700 dark:text-gray-300">Point of Interest</span>
            </div>
        </div>
    </div>
</div>

<script>
    function floorPlanViewer(floorPlanData) {
        return {
            floorPlanData: floorPlanData,
            canvas: null,
            ctx: null,
            floorPlanImage: null,
            selectedAnnotation: null,
            hoveredAnnotation: null,
            
            init() {
                this.canvas = this.$refs.canvas;
                this.ctx = this.canvas.getContext('2d');
                
                if (this.floorPlanData && this.floorPlanData.image) {
                    this.loadImage(this.floorPlanData.image);
                }
            },
            
            loadImage(imageSrc) {
                const img = new Image();
                img.onload = () => {
                    this.floorPlanImage = img;
                    
                    // Set canvas size to match container while maintaining aspect ratio
                    const maxWidth = this.canvas.parentElement.clientWidth;
                    const scale = maxWidth / img.width;
                    
                    this.canvas.width = img.width;
                    this.canvas.height = img.height;
                    this.canvas.style.maxWidth = '100%';
                    this.canvas.style.height = 'auto';
                    
                    this.redraw();
                };
                img.src = imageSrc;
            },
            
            handleCanvasClick(event) {
                const annotation = this.getAnnotationAtPoint(event);
                if (annotation) {
                    this.selectedAnnotation = annotation;
                } else {
                    this.selectedAnnotation = null;
                }
                this.redraw();
            },
            
            handleMouseMove(event) {
                const annotation = this.getAnnotationAtPoint(event);
                this.hoveredAnnotation = annotation;
                this.canvas.style.cursor = annotation ? 'pointer' : 'default';
                this.redraw();
            },
            
            getAnnotationAtPoint(event) {
                const rect = this.canvas.getBoundingClientRect();
                const scaleX = this.canvas.width / rect.width;
                const scaleY = this.canvas.height / rect.height;
                const x = (event.clientX - rect.left) * scaleX;
                const y = (event.clientY - rect.top) * scaleY;
                
                const annotations = this.floorPlanData.annotations || [];
                for (let i = annotations.length - 1; i >= 0; i--) {
                    const annotation = annotations[i];
                    const radius = annotation.type === 'room' ? 20 : 15;
                    const distance = Math.sqrt(Math.pow(x - annotation.x, 2) + Math.pow(y - annotation.y, 2));
                    
                    if (distance <= radius) {
                        return annotation;
                    }
                }
                return null;
            },
            
            redraw() {
                if (!this.ctx || !this.floorPlanImage) return;
                
                // Clear canvas
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                
                // Draw floor plan image
                this.ctx.drawImage(this.floorPlanImage, 0, 0);
                
                // Draw annotations
                const annotations = this.floorPlanData.annotations || [];
                annotations.forEach((annotation) => {
                    const isSelected = this.selectedAnnotation === annotation;
                    const isHovered = this.hoveredAnnotation === annotation;
                    
                    if (annotation.type === 'room') {
                        // Draw room marker (circle)
                        this.ctx.fillStyle = isSelected || isHovered ? 'rgba(59, 130, 246, 0.9)' : 'rgba(59, 130, 246, 0.7)';
                        this.ctx.beginPath();
                        this.ctx.arc(annotation.x, annotation.y, 20, 0, 2 * Math.PI);
                        this.ctx.fill();
                        this.ctx.strokeStyle = isSelected ? '#1e3a8a' : '#1e40af';
                        this.ctx.lineWidth = isSelected ? 3 : 2;
                        this.ctx.stroke();
                    } else {
                        // Draw marker (pin)
                        this.ctx.fillStyle = isSelected || isHovered ? 'rgba(239, 68, 68, 0.9)' : 'rgba(239, 68, 68, 0.7)';
                        this.ctx.beginPath();
                        this.ctx.arc(annotation.x, annotation.y, 15, 0, 2 * Math.PI);
                        this.ctx.fill();
                        this.ctx.strokeStyle = isSelected ? '#7f1d1d' : '#991b1b';
                        this.ctx.lineWidth = isSelected ? 3 : 2;
                        this.ctx.stroke();
                    }
                    
                    // Draw label
                    this.ctx.fillStyle = '#000';
                    this.ctx.font = 'bold 14px Arial';
                    this.ctx.fillText(annotation.label, annotation.x + 25, annotation.y + 5);
                });
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endif
