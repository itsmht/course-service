@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Course</h2>

    {{-- Show success message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('courses.store') }}" method="POST">
        @csrf

        {{-- COURSE DETAILS --}}
        <div class="card mb-4 p-4">
            <h4>Course Details</h4>
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
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
                <label class="form-label">Featured Image URL</label>
                <input type="text" name="featured_image_url" class="form-control">
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
            <button type="button" id="add-instructor" class="btn btn-sm btn-secondary mt-2">+ Add Instructor</button>
        </div>

        {{-- MODULES --}}
        <div class="card mb-4 p-4">
            <h4>Modules</h4>
            <div id="modules-container">
                <div class="module-item border p-3 mb-2 rounded">
                    <input type="text" name="modules[0][title]" class="form-control mb-2" placeholder="Module Title" required>
                    <textarea name="modules[0][description]" class="form-control mb-2" placeholder="Module Description" required></textarea>
                    <input type="number" name="modules[0][module_order]" class="form-control mb-2" placeholder="Order (optional)">
                </div>
            </div>
            <button type="button" id="add-module" class="btn btn-sm btn-secondary mt-2">+ Add Module</button>
        </div>

        <button type="submit" class="btn btn-primary">Save Course</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    let instructorsData = [];

    // Fetch instructors from API
    try {
        const response = await fetch('https://auth.transformbd.com/api/instructors');
        instructorsData = await response.json();
        populateInstructorSelect(document.querySelector('.instructor-select'), instructorsData);
    } catch (e) {
        console.error('Error loading instructors:', e);
    }

    // Populate instructor dropdown
    function populateInstructorSelect(selectElem, instructors) {
        instructors.forEach(inst => {
            const option = document.createElement('option');
            option.value = inst.account_id;
            option.textContent = inst.name;
            selectElem.appendChild(option);
        });
    }

    // Add new instructor dropdown
    let instructorCount = 1;
    document.getElementById('add-instructor').addEventListener('click', () => {
        const container = document.getElementById('instructors-container');
        const div = document.createElement('div');
        div.classList.add('instructor-item', 'mb-3');
        div.innerHTML = `
            <div class="d-flex gap-2">
                <select name="instructors[${instructorCount}]" class="form-select instructor-select" required>
                    <option value="">Select Instructor</option>
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-instructor">Remove</button>
            </div>
        `;
        container.appendChild(div);
        populateInstructorSelect(div.querySelector('.instructor-select'), instructorsData);
        instructorCount++;
    });

    // Remove instructor field
    document.getElementById('instructors-container').addEventListener('click', e => {
        if (e.target.classList.contains('remove-instructor')) {
            e.target.closest('.instructor-item').remove();
        }
    });

    // Add modules dynamically
    let moduleCount = 1;
    document.getElementById('add-module').addEventListener('click', () => {
        const container = document.getElementById('modules-container');
        const div = document.createElement('div');
        div.classList.add('module-item', 'border', 'p-3', 'mb-2', 'rounded');
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <strong>Module ${moduleCount + 1}</strong>
                <button type="button" class="btn btn-danger btn-sm remove-module">Remove</button>
            </div>
            <input type="text" name="modules[${moduleCount}][title]" class="form-control mb-2" placeholder="Module Title" required>
            <textarea name="modules[${moduleCount}][description]" class="form-control mb-2" placeholder="Module Description" required></textarea>
            <input type="number" name="modules[${moduleCount}][module_order]" class="form-control mb-2" placeholder="Order (optional)">
        `;
        container.appendChild(div);
        moduleCount++;
    });

    // Remove module
    document.getElementById('modules-container').addEventListener('click', e => {
        if (e.target.classList.contains('remove-module')) {
            e.target.closest('.module-item').remove();
        }
    });
});
</script>
@endsection
