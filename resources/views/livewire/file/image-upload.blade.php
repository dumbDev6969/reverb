<div>
      @if (session('success'))
        <flux:callout icon="bell" variant="secondary" inline x-data="{ visible: true }" x-show="visible">
            <flux:callout.heading class="flex gap-2 @max-md:flex-col items-start">{{ session('success') }}<flux:text>{{ session('time') }}
                </flux:text>
            </flux:callout.heading>
            <x-slot name="controls">
                <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
            </x-slot>
        </flux:callout>
    @else
        <flux:callout icon="bell" variant="secondary" inline x-data="{ visible: true }" x-show="visible">
            <flux:callout.heading class="flex gap-2 @max-md:flex-col items-start">{{ session('error') }} <flux:text>{{ session('time') }}
                </flux:text>
            </flux:callout.heading>
            <x-slot name="controls">
                <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
            </x-slot>
        </flux:callout>
    @endif
    {{-- Secure File Upload Component --}}
    <form wire:submit.prevent="save" x-data="fileUpload()" class="mx-auto w-full max-w-md">

        {{-- Dropzone --}}
        <div class="flex items-center justify-center w-full" @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false" @drop.prevent="handleDrop($event)">
            <label for="dropzone-file"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer 
                       bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 
                       dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                :class="{ 'border-blue-500 bg-blue-50': dragging }">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5
                                 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5
                                 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        SVG, PNG, JPG or GIF (MAX. 800x400px)
                    </p>
                </div>

                {{-- Livewire File Input --}}
                <input id="dropzone-file" type="file" class="hidden" wire:key="upload-{{ $uploadKey }}"
                    wire:model.live="image" @change="handleFiles($event)" />
            </label>
        </div>

        {{-- Livewire Validation Feedback --}}
        @error('image')
            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
        @enderror

        {{-- Preview --}}
        <template x-if="file">
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Selected: <span x-text="file.name"></span></p>
                <img :src="preview" alt="Preview" class="mx-auto mt-2 h-32 object-contain rounded-lg border" />
            </div>
        </template>

        {{-- Upload Button --}}
        <div class="mt-6 text-center">
            <flux:button type="submit" variant="primary">Upload</flux:button>
        </div>

    </form>

  

    {{-- Alpine Script --}}
    <script>
        function fileUpload() {
            return {
                file: null,
                preview: null,
                dragging: false,
                handleFiles(event) {
                    const files = event.target.files;
                    if (files.length) {
                        this.file = files[0];
                        this.previewFile();
                    }
                },
                handleDrop(event) {
                    this.dragging = false;
                    const files = event.dataTransfer.files;
                    if (files.length) {
                        this.file = files[0];
                        this.previewFile();
                    }
                },
                previewFile() {
                    if (!this.file) return;
                    const reader = new FileReader();
                    reader.onload = e => this.preview = e.target.result;
                    reader.readAsDataURL(this.file);
                }
            };
        }
    </script>
</div>
