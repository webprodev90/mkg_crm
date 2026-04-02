/**
* Theme: Highdmin - Responsive Bootstrap 4 Admin Dashboard
* Author: Coderthemes
* Tooltips
*/

(function ($) {

    "use strict";


    $('#tooltip-hover').tooltipster();

    $('#tooltip-events').tooltipster({
        trigger: 'click'
    });

    $('#tooltip-html').tooltipster({
        content: $('<p style="text-align:left;">Наши лиды проходят фильтрацию колл центром, после прохождения квалификации отправляеться Вам! Имеют отличную конверсию! Наш лид уже ждет звонка Вашего юриста! Быстрый запуск. </p><p style="text-align:left;"> Всю информацию уточняйте у менеджеров.</p> '),
        // setting a same value to minWidth and maxWidth will result in a fixed width
        minWidth: 300,
        maxWidth: 300,
        position: 'right'
    });
    $('#tooltip-html1').tooltipster({
        content: $('<p style="text-align:left;">Наши лиды в ипотечной среде могут быть классифицированы по типу проблемы, с которой они обращаются к ипотечным брокерам. Обычно потенциальные клиенты ищут:</p><p style="text-align:left;">1) Консультации по ипотечному кредитованию, подбор оптимальных программ и предложений на рынке.</p><p style="text-align:left;">2) Помощь в оформлении пакета документов, советы по составлению бумаг.</p><p style="text-align:left;">3) Представления интересов клиента перед банковскими и иными организациями для обеспечения максимально выгодных условий кредитования.</p><p style="text-align:left;">4) Информации обо всех особенностях и правилах кредитования в сфере ипотеки.</p><p style="text-align:left;"> Всю информацию уточняйте у менеджеров.</p> '),
        // setting a same value to minWidth and maxWidth will result in a fixed width
        minWidth: 300,
        maxWidth: 300,
        position: 'right'
    });
 
    $('#tooltip-html2').tooltipster({
        content: $('<p style="text-align:left;">Обращения граждан по решению юридических вопросов. Настраиваем общий поток заявок. Так же индивидуальные рекламные компании.</p><p style="text-align:left;"> Всю информацию уточняйте у менеджеров.</p> '),
        // setting a same value to minWidth and maxWidth will result in a fixed width
        minWidth: 300,
        maxWidth: 300,
        position: 'right'
    });		
    $('#tooltip-touch').tooltipster({
        touchDevices: false
    });

    $('#tooltip-animation').tooltipster({
        animation: 'grow'
    });

    $('#tooltip-interaction').tooltipster({
        contentAsHTML: true,
        interactive: true
    });

    // Multiple tooltips
    $('#tooltip-multiple').tooltipster({
        animation: 'swing',
        content: 'North',
        multiple: true,
        position: 'top'
      });

      $('#tooltip-multiple').tooltipster({
        content: 'East',
        multiple: true,
        position: 'right'
      });

      $('#tooltip-multiple').tooltipster({
        animation: 'grow',
        content: 'South',
        delay: 200,
        multiple: true,
        position: 'bottom'
      });

      $('#tooltip-multiple').tooltipster({
        animation: 'fall',
        content: 'West',
        multiple: true,
        position: 'left'
      });

})(jQuery);