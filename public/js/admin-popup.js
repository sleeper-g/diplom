// Функция для открытия popup
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

// Функция для закрытия popup
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Закрытие popup при клике вне его области
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.popup').forEach(function(popup) {
        popup.addEventListener('click', function(e) {
            // Закрываем popup если клик был на самом popup или на контейнере (но не на содержимом)
            if (e.target === this || (e.target.classList.contains('popup__container') && e.target === popup.querySelector('.popup__container'))) {
                closePopup(this.id);
            }
        });
        
        // Предотвращаем закрытие при клике на содержимое popup
        const popupContent = popup.querySelector('.popup__content');
        if (popupContent) {
            popupContent.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });

    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.popup.active').forEach(function(popup) {
                closePopup(popup.id);
            });
        }
    });
});

// Функция для открытия popup удаления зала
function openRemoveHallPopup(hallId, hallName, route) {
    const popup = document.getElementById('popup-remove-hall');
    const form = document.getElementById('form-remove-hall');
    const nameSpan = document.getElementById('hall-name');
    
    if (popup && form && nameSpan) {
        nameSpan.textContent = '"' + hallName + '"';
        form.action = route;
        openPopup('popup-remove-hall');
    }
}

// Функция для открытия popup удаления фильма
function openRemoveFilmPopup(filmId, filmName, route) {
    const popup = document.getElementById('popup-remove-film');
    const form = document.getElementById('form-remove-film');
    const nameSpan = document.getElementById('film-name');
    
    if (popup && form && nameSpan) {
        nameSpan.textContent = '"' + filmName + '"';
        form.action = route;
        openPopup('popup-remove-film');
    }
}

// Функция для открытия popup удаления сеанса
function openRemoveSeancePopup(seanceId, movieName, route) {
    const popup = document.getElementById('popup-remove-seance');
    const form = document.getElementById('form-remove-seance');
    const nameSpan = document.getElementById('seance-movie-name');
    
    if (popup && form && nameSpan) {
        nameSpan.textContent = '"' + movieName + '"';
        form.action = route;
        openPopup('popup-remove-seance');
    }
}


