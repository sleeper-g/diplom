<div class="popup" id="popup-add-film">
    <div class="popup__container">
        <div class="popup__content">
            <div class="popup__header">
                <h2 class="popup__title">
                    Добавление фильма
                    <a class="popup__dismiss" href="#" onclick="closePopup('popup-add-film'); return false;">
                        <img src="{{ asset('i/admin/close.png') }}" alt="Закрыть">
                    </a>
                </h2>
            </div>
            <div class="popup__wrapper">
                <form action="{{ route('admin.movies.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    <div class="popup__form-container">
                        <div class="popup__poster">
                            <label class="conf-step__label conf-step__label-fullsize" for="poster">
                                Постер фильма
                                <input class="conf-step__input" type="file" name="poster" accept="image/*" id="poster">
                                <small style="display: block; margin-top: 5px; color: #666;">Макс. размер: 2MB. Форматы: JPEG, PNG, JPG, GIF</small>
                            </label>
                            <div id="poster-preview" style="margin-top: 10px; display: none;">
                                <img id="poster-preview-img" src="" alt="Предпросмотр постера" style="max-width: 200px; max-height: 300px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        </div>
                        <div class="popup__form">
                            <label class="conf-step__label conf-step__label-fullsize" for="title">
                                Название фильма
                                <input class="conf-step__input" type="text" placeholder="Например, &laquo;Гражданин Кейн&raquo;" name="title" required>
                            </label>
                            <label class="conf-step__label conf-step__label-fullsize" for="duration">
                                Продолжительность фильма (мин.)
                                <input class="conf-step__input" type="number" min="1" max="300" name="duration" required>
                            </label>
                            <label class="conf-step__label conf-step__label-fullsize" for="description">
                                Описание фильма
                                <textarea class="conf-step__input" name="description" required></textarea>
                            </label>
                        </div>
                    </div>
                    <div class="conf-step__buttons text-center">
                        <input type="submit" value="Добавить фильм" class="conf-step__button conf-step__button-accent">
                        <button class="conf-step__button conf-step__button-regular" type="button" onclick="closePopup('popup-add-film'); return false;">Отменить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const posterInput = document.getElementById('poster');
    const posterPreview = document.getElementById('poster-preview');
    const posterPreviewImg = document.getElementById('poster-preview-img');
    
    if (posterInput) {
        posterInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    posterPreviewImg.src = e.target.result;
                    posterPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                posterPreview.style.display = 'none';
            }
        });
    }
});
</script>

