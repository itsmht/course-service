@extends('layout')

@section('content')
<div class="container">
    <h2>Create Course</h2>

    {{-- Show success message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- COURSE DETAILS --}}
        <div class="card mb-4 p-4">
            <h4>Course Details</h4>
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
            <label class="form-label">Preview on Homepage?</label>
            <select name="preview_homepage" class="form-select" required>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Other Information</label>
                <textarea name="other_information" class="form-control" rows="4" placeholder="Enter any additional info..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="text" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Featured Video URL</label>
                <input type="text" name="featured_video_url" class="form-control">
            </div>

            <div class="mb-3">
            <label class="form-label">Featured Image</label>
            <input type="file" name="featured_image" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Tagline</label>
                <input type="text" name="tagline" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Time</label>
                <input type="integer" name="time" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Capacity</label>
                <input type="text" name="capacity" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <input type="text" name="type" class="form-control">
            </div>
        </div>

        {{-- INSTRUCTORS --}}
        <div class="card mb-4 p-4">
            <h4>Instructors</h4>
            <div id="instructors-container">
                <div class="instructor-item mb-3">
                    <div class="d-flex gap-2">
                        <select name="instructors[0]" class="form-select instructor-select" required>
                            <option value="">Select Instructor</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="button" id="addInstructorBtn" class="btn btn-sm btn-secondary mt-2">+ Add Instructor</button>
        </div>

        {{-- MODULES --}}
        <div class="card mb-4 p-4">
    <h4>Modules</h4>
    <div id="modules-container">
        <div class="module-item border p-3 mb-2 rounded">
            <input type="text" name="modules[0][title]" class="form-control mb-2" placeholder="Module Title" required>
            
            <textarea name="modules[0][description]" class="form-control mb-2 d-none module-description-input" placeholder="Module Description" required></textarea>
            <div class="description-editor" style="height: 150px;"></div> <input type="number" name="modules[0][module_order]" class="form-control mt-2" placeholder="Order (optional)">
        </div>
    </div>
    <button type="button" id="addModuleBtn" class="btn btn-sm btn-secondary mt-2">+ Add Module</button>
</div>

        <button type="submit" class="btn btn-primary">Save Course</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    let instructorsData = [];
    let instructorIndex = 1; // Start index for new instructors (0 is in HTML)
    let moduleIndex = 1;     // Start index for new modules (0 is in HTML)

    //Quill Editor Initialization for existing module description
    const quillOptions = {
        theme: 'snow', // 'snow' is the standard toolbar theme
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'link'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }]
            ]
        }
    };

    /**
     * Finds a <div> and its preceding hidden <textarea> and initializes Quill.
     * When the editor content changes, it updates the hidden textarea's value.
     */
    function initializeQuill(editorElement) {
        // Find the hidden textarea right before this editor <div>
        const hiddenTextarea = editorElement.previousElementSibling;

        if (!hiddenTextarea || hiddenTextarea.tagName !== 'TEXTAREA') {
            console.error('Could not find hidden textarea for Quill editor:', editorElement);
            return;
        }

        // Initialize Quill on the <div>
        const quill = new Quill(editorElement, quillOptions);

        // Set the editor's initial content from the textarea (if any)
        quill.root.innerHTML = hiddenTextarea.value;

        // Listen for changes and update the hidden textarea
        quill.on('text-change', () => {
            // Update the hidden textarea's value with the editor's HTML
            hiddenTextarea.value = quill.root.innerHTML;
        });
    }

    try {
        const response = await fetch('https://auth.transformbd.com/api/instructors');
        const result = await response.json();

        if (result.data && Array.isArray(result.data)) {
            instructorsData = result.data;
        } else {
            console.error('Unexpected instructor API format:', result);
        }

        // Populate the first dropdown
        populateInstructorSelect(document.querySelector('.instructor-select'), instructorsData);
    } catch (e) {
        console.error('Error loading instructors:', e);
    }

    // Function to fill instructor select dropdown
    function populateInstructorSelect(selectElem, instructors) {
        selectElem.innerHTML = '<option value="">Select Instructor</option>';
        instructors.forEach(inst => {
            const option = document.createElement('option');
            option.value = inst.account_id;
            option.textContent = inst.name;
            selectElem.appendChild(option);
        });
    }

    // Add new instructor dropdown
    document.getElementById('addInstructorBtn').addEventListener('click', () => {
        const newInstructorDiv = document.createElement('div');
        newInstructorDiv.classList.add('instructor-item', 'mb-3'); // Match HTML style

        // Use index for consistent naming
        const index = instructorIndex++;
        
        newInstructorDiv.innerHTML = `
            <div class="d-flex gap-2">
                <select name="instructors[${index}]" class="form-select instructor-select" required></select>
                <button type="button" class="btn btn-danger remove-instructor">Remove</button>
            </div>
        `;
        
        // ✅ FIX 1: Use the correct container ID
        document.getElementById('instructors-container').appendChild(newInstructorDiv);

        // Fill the new select with instructors
        populateInstructorSelect(newInstructorDiv.querySelector('.instructor-select'), instructorsData);
    });

    // Remove instructor dropdown
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-instructor')) {
            e.target.closest('.instructor-item').remove();
        }
    });

    // Add module fields
    document.getElementById('addModuleBtn').addEventListener('click', () => {
        const newModule = document.createElement('div');
        newModule.classList.add('module-item', 'border', 'p-3', 'mb-2', 'rounded'); // Match HTML style

        // Use index for consistent naming
        const index = moduleIndex++;

        newModule.innerHTML = `
            <input type="text" name="modules[${index}][title]" class="form-control mb-2" placeholder="Module Title" required>
            <textarea name="modules[${index}][description]" class="form-control mb-2" placeholder="Module Description" required></textarea>
            <input type="number" name="modules[${index}][module_order]" class="form-control mb-2" placeholder="Order (optional)">
            <button type="button" class="btn btn-danger remove-module" style="float: right;">Remove Module</button>
        `;
        
        // ✅ FIX 2: Use the correct container ID
        document.getElementById('modules-container').appendChild(newModule);
    });

    // Remove module
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-module')) {
            e.target.closest('.module-item').remove();
        }
    });
});
</script>
@endsection
