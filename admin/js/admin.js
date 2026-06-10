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
            // перекидываем файл в скрытый input
            var dt = new DataTransfer();
            dt.items.add(files[0]);
            fileInput.files = dt.files;
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

// модал подтверждения удаления
var deleteModal = document.getElementById('delete-modal');
var deleteModalName = document.getElementById('delete-modal-name');
var deleteConfirmBtn = document.getElementById('delete-confirm-btn');
var deleteCancelBtn = document.getElementById('delete-cancel-btn');
var pendingDeleteUrl = '';

// вешаем обработчики на все кнопки удаления
var deleteBtns = document.querySelectorAll('.js-delete-btn');
deleteBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
        var name = btn.getAttribute('data-name');
        var url  = btn.getAttribute('data-url');
        pendingDeleteUrl = url;
        if (deleteModalName) deleteModalName.textContent = '"' + name + '"';
        if (deleteModal) deleteModal.classList.add('open');
    });
});

if (deleteConfirmBtn) {
    deleteConfirmBtn.addEventListener('click', function() {
        if (pendingDeleteUrl) {
            window.location.href = pendingDeleteUrl;
        }
    });
}

if (deleteCancelBtn) {
    deleteCancelBtn.addEventListener('click', function() {
        if (deleteModal) deleteModal.classList.remove('open');
        pendingDeleteUrl = '';
    });
}

// закрыть модал по клику на оверлей
if (deleteModal) {
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.remove('open');
            pendingDeleteUrl = '';
        }
    });
}
