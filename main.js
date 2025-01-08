jQuery(document).ready(function($) {
    // добавляем маску для телефона
    $('input[type="tel"]').mask("+7 (999) 999-99-99");

    // Генерируем случайное число от 1000 до 99999 
    var randomNumber = Math.floor(Math.random() * 90000) + 1000; 
    // Вставляем это число в input с id "inputPrice" 
    $('#inputPrice').val(randomNumber);

    // Если пользователь провел на сайте больше 30 секунд меняем значение скрытого инпута longTime с 0 на 1  
    setTimeout(function() {
        $('#longTime').val('1');
    }, 30000); 

    $('#amo-form').on('submit', function(e) {
        // Запрещаем отправку формы по нажатию Enter
        e.preventDefault();
        
        $.ajax({
            url: '/ajax/amoCrmHandler.php',
            method: 'POST',
            dataType: 'html',
            data: $(this).serialize(),
            success: function(data){
                alert(data);
            }
        });
    });
});