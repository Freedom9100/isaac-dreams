// бургер меню (мобильный сайдбар)
var burger = document.getElementById('admin-burger');
var sidebar = document.getElementById('admin-sidebar');
var overlay = document.getElementById('sidebar-overlay');

function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('open');
}

function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
}

if (burger) {
    burger.addEventListener('click', openSidebar);
}

if (overlay) {
    overlay.addEventListener('click', closeSidebar);
}

// drag-drop зона загрузки файла
var dropZone = document.getElementById('drop-zone');
var fileInput = document.getElementById('file-input');
var dropFilename = document.getElementById('drop-filename');
var dropText = document.getElementById('drop-text');

if (dropZone && fileInput) {
    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            showFile(fileInput.files[0].name);
        }
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', function() {
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        var files = e.dataTransfer.files;
        if (files.length > 0) {
            showFile(files[0].name);
        }
    });
}

function showFile(name) {
    if (dropText) dropText.style.display = 'none';
    if (dropFilename) {
        dropFilename.style.display = 'block';
        dropFilename.textContent = name;
    }
}
