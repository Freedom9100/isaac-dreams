// переключение вкладок профиля
var tabBtns     = document.querySelectorAll('.ftab-btn');
var tabContents = document.querySelectorAll('.profile-tab-content');

tabBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
        var target = btn.getAttribute('data-tab');
        tabBtns.forEach(function(b) { b.classList.remove('active'); });
        tabContents.forEach(function(c) { c.style.display = 'none'; });
        btn.classList.add('active');
        document.getElementById('tab-' + target).style.display = 'block';
    });
});

// модал редактирования имени
var editNameBtn    = document.getElementById('edit-name-btn');
var editNameModal  = document.getElementById('edit-name-modal');
var editNameCancel = document.getElementById('edit-name-cancel');

function openEditModal() {
    editNameModal.classList.add('open');
}

function closeEditModal() {
    editNameModal.classList.remove('open');
}

if (editNameBtn) {
    editNameBtn.addEventListener('click', openEditModal);
}

if (editNameCancel) {
    editNameCancel.addEventListener('click', closeEditModal);
}

if (editNameModal) {
    editNameModal.addEventListener('click', function(e) {
        if (e.target === editNameModal) {
            closeEditModal();
        }
    });
}
