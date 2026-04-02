<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';*/
/*		$pass = 'nopL908M*r';
		$salt = '09f8a8c2';
 
		$i_passmd5 = md5(md5($pass).$salt);
		print_r($i_passmd5);*/
		
		
?>



<form id="amoforms_form" class="amoforms-form amoforms-view-form" action="https://crmdev.mkggroup.ru/mail.php" method="POST" enctype="multipart/form-data" style="width:55%;padding-bottom:61px;">        
                              
        <div class="amoforms__fields__container amoforms__fields__container_text amoforms__fields__container_inside">
  <div class="amoforms__fields__container__inner amoforms__fields__container__inner_text  amoforms__fields__container__inner_inside ">
          <div class="amoforms__field__name amoforms__field__name_text" title="ФИО">
                  <label class="amoforms__field__name-label">
            <div>ФИО</div>
          </label>
                <span class="amoforms__field__required">*</span>
      </div>
    
    <div class="amoforms__field__control amoforms__field__control_text js-amoforms-border-radius js-amoforms-field-height">

              <input type="text" name="fields[name_1]" placeholder="ФИО" class="amoforms_placeholder js-form-changes-skip text-input js-amoforms-font js-amoforms-color  amoforms-validate_required">      
                </div>
                  <div class="amoforms__field__required-inside">*</div>      </div>
</div>
                                        
          <div class="amoforms__fields__container amoforms__fields__container_multitext amoforms__fields__container_inside">
  <div class="amoforms__fields__container__inner amoforms__fields__container__inner_multitext  amoforms__fields__container__inner_inside ">
          <div class="amoforms__field__name amoforms__field__name_multitext" title="Телефон">
                  <label class="amoforms__field__name-label">
            <div>Телефон</div>
          </label>
                <span class="amoforms__field__required">*</span>
      </div>
    
    <div class="amoforms__field__control amoforms__field__control_multitext js-amoforms-border-radius js-amoforms-field-height">

                
<input type="tel" class="amoforms_placeholder js-form-changes-skip text-input js-amoforms-font js-amoforms-color  amoforms-validate_required amoforms-validate_phone" name="fields[288759_1][570137]" placeholder="Телефон">      
                </div>
                  <div class="amoforms__field__required-inside">*</div>      </div>
</div>
                                        
      <div class="amoforms__fields__container amoforms__fields__container_multiselect amoforms__fields__container_inside">
  <div class="amoforms__fields__container__inner amoforms__fields__container__inner_multiselect  amoforms__fields__container__inner_inside ">
          <div class="amoforms__field__name amoforms__field__name_multiselect" title="Сумма долгов">
                  <label class="amoforms__field__name-label">
            <div>Сумма долгов</div>
          </label>
                <span class="amoforms__field__required" style="display: none">*</span>
      </div>
    
    <div class="amoforms__field__control amoforms__field__control_multiselect js-amoforms-border-radius js-amoforms-field-height" style="">

              <div class="checkboxes_dropdown " data-selected="false">
  <div class="checkboxes_dropdown__title_wrapper">
    <span class="checkboxes_dropdown__checkbox_master icon icon-checkbox"></span>
    <span class="checkboxes_dropdown__title-selected">
      <span class="checkboxes_dropdown__selected"></span><!--
      --><span class="checkboxes_dropdown__title" data-numeral="значение,значения,значений,значения" data-title-empty="Сумма долгов">Сумма долгов</span>
    </span>
    <span class="checkboxes_dropdown_icon icon-v-ico-2"></span>
  </div>
  <div class="checkboxes_dropdown__list custom-scroll js-amoforms-border-radius" style="display: none;">
    <div class="checkboxes_dropdown__list__wrapper__inner">
      <div class="checkboxes_dropdown__item">
        <label class="control-checkbox checkboxes_dropdown__label js-master-checkbox-wrapper  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="js-form-changes-skip js-master-checkbox" name="" id="cbx_drop_master_905" value="" data-value="">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text js-select-all-text checkboxes_dropdown__label_title checkboxes_dropdown__label_title-not_active" title="Выбрать всё">
                        Выбрать всё
                  </div>
  </label>
      </div>
      
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1298273]" id="fields[685497_2]_1298273" value="1298273" data-value="1298273">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="Не выявлена">
                        Не выявлена
                  </div>
  </label>
        </div>
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1298265]" id="fields[685497_2]_1298265" value="1298265" data-value="1298265">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="до 100 к руб">
                        до 100 к руб
                  </div>
  </label>
        </div>
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1298267]" id="fields[685497_2]_1298267" value="1298267" data-value="1298267">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="100-300 к">
                        100-300 к
                  </div>
  </label>
        </div>
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1298269]" id="fields[685497_2]_1298269" value="1298269" data-value="1298269">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="300-600 к">
                        300-600 к
                  </div>
  </label>
        </div>
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1363183]" id="fields[685497_2]_1363183" value="1363183" data-value="1363183">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="600 - 1 млн">
                        600 - 1 млн
                  </div>
  </label>
        </div>
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1363185]" id="fields[685497_2]_1363185" value="1363185" data-value="1363185">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="1 - 3 млн">
                        1 - 3 млн
                  </div>
  </label>
        </div>
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1363573]" id="fields[685497_2]_1363573" value="1363573" data-value="1363573">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="3 - 5 млн">
                        3 - 5 млн
                  </div>
  </label>
        </div>
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1363187]" id="fields[685497_2]_1363187" value="1363187" data-value="1363187">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="больше 5 млн руб">
                        больше 5 млн руб
                  </div>
  </label>
        </div>
              
        
        <div class="checkboxes_dropdown__item   " style="">
                                
          
          
          <label class="control-checkbox checkboxes_dropdown__label  ">
        
  <div class="control-checkbox__body">
    <input type="checkbox" class="" name="fields[685497_2][1298271]" id="fields[685497_2]_1298271" value="1298271" data-value="1298271">

    <span class="control-checkbox__helper "></span>
  </div>

      <div class="control-checkbox__text element__text checkboxes_dropdown__label_title" title="Ипотечник">
                        Ипотечник
                  </div>
  </label>
        </div>
          </div>
  </div>
</div>
      
                </div>
                </div>
</div>
                                        
      <div class="amoforms__fields__container amoforms__fields__container_textarea amoforms__fields__container_inside">
  <div class="amoforms__fields__container__inner amoforms__fields__container__inner_textarea  amoforms__fields__container__inner_inside ">
          <div class="amoforms__field__name amoforms__field__name_textarea" title="Залоговое имущество">
                  <label class="amoforms__field__name-label">
            <div>Залоговое имущество</div>
          </label>
                <span class="amoforms__field__required" style="display: none">*</span>
      </div>
    
    <div class="amoforms__field__control amoforms__field__control_textarea js-amoforms-border-radius ">

              <textarea class="js-form-changes-skip amoforms__textarea text-input js-amoforms-font js-amoforms-color js-amoforms-border-radius custom-scroll " name="fields[819439_2]" placeholder="Залоговое имущество"></textarea>      
                </div>
                </div>
</div>
                                        
      <div class="amoforms__fields__container amoforms__fields__container_radiobutton amoforms__fields__container_inside">
  <div class="amoforms__fields__container__inner amoforms__fields__container__inner_radiobutton  amoforms__fields__container__inner_inside ">
          <div class="amoforms__field__name amoforms__field__name_radiobutton" title="Есть ли просрочки ?">
                  <label class="amoforms__field__name-label">
            <div>Есть ли просрочки ?</div>
          </label>
                <span class="amoforms__field__required" style="display: none">*</span>
      </div>
    
    <div class="amoforms__field__control amoforms__field__control_radiobutton js-amoforms-border-radius ">

              <div class="amoforms__field__control_radio-wrapper ">
      <label class="amoforms__radio js-amoforms-font js-amoforms-color">
      <div class="amoforms__radio__icon">
        <input type="radio" name="fields[820215_2]" class="fields[820215_2]" value="1362253">
        <div class="amoforms__radio__helper"></div>
      </div>
      <div>Нет</div>
    </label>
      <label class="amoforms__radio js-amoforms-font js-amoforms-color">
      <div class="amoforms__radio__icon">
        <input type="radio" name="fields[820215_2]" class="fields[820215_2]" value="1362251">
        <div class="amoforms__radio__helper"></div>
      </div>
      <div>Есть</div>
    </label>
      <label class="amoforms__radio js-amoforms-font js-amoforms-color">
      <div class="amoforms__radio__icon">
        <input type="radio" name="fields[820215_2]" class="fields[820215_2]" value="1364067">
        <div class="amoforms__radio__helper"></div>
      </div>
      <div>Без значения</div>
    </label>
  </div>
      
                </div>
      </div>
</div>
                                        
      <div class="amoforms__fields__container amoforms__fields__container_text amoforms__fields__container_inside">
  <div class="amoforms__fields__container__inner amoforms__fields__container__inner_text  amoforms__fields__container__inner_inside ">
          <div class="amoforms__field__name amoforms__field__name_text" title="ГЕО">
                  <label class="amoforms__field__name-label">
            <div>ГЕО</div>
          </label>
                <span class="amoforms__field__required" style="display: none">*</span>
      </div>
    
    <div class="amoforms__field__control amoforms__field__control_text js-amoforms-border-radius js-amoforms-field-height">

              <input type="text" name="fields[826429_2]" placeholder="ГЕО" class="amoforms_placeholder js-form-changes-skip text-input js-amoforms-font js-amoforms-color ">      
                </div>
                </div>
</div>
                                        
      <div class="amoforms__fields__container amoforms__fields__container_text amoforms__fields__container_inside">
  <div class="amoforms__fields__container__inner amoforms__fields__container__inner_text  amoforms__fields__container__inner_inside ">
          <div class="amoforms__field__name amoforms__field__name_text" title="Комментарий">
                  <label class="amoforms__field__name-label">
            <div>Комментарий</div>
          </label>
                <span class="amoforms__field__required" style="display: none">*</span>
      </div>
    
    <div class="amoforms__field__control amoforms__field__control_text js-amoforms-border-radius js-amoforms-field-height">

              <input type="text" name="fields[826359_2]" placeholder="Комментарий" class="amoforms_placeholder js-form-changes-skip text-input js-amoforms-font js-amoforms-color ">      
                </div>
                </div>
</div>
                  
        <input type="hidden" name="form_id" id="form_id" value="1434494">
        <input type="hidden" name="hash" value="d4bf52dd275a5cef7a0c7e868c603e3b">
        <input type="hidden" name="user_origin" id="user_origin" value="{&quot;datetime&quot;:&quot;Tue Feb 25 2025 13:41:18 GMT+0400 (GMT+04:00)&quot;,&quot;referer&quot;:&quot;&quot;}">

        
                          
        <div class="amoforms__fields__submit">
          <div class="amoforms__submit-button__flex amoforms__submit-button__flex_center">
            
            <button class="amoforms__submit-button amoforms__submit-button_rounded amoforms__submit-button_width text-input js-form-changes-skip js-amoforms-font js-amoforms-field-height" type="submit" id="button_submit" style="width: 369px;color: #FFFFFF;
                background-color: #315fb5;
                border-radius: 53px;
                font-weight: bold;                                ">
              <span class="amoforms__spinner-icon"></span>
              <span class="amoforms__submit-button-text">Отправить</span>
            </button>
          </div>
        </div>
        <div id="amoforms__fields__error-required"></div>
        <div id="amoforms__fields__error-typo"></div>
        <input type="hidden" id="amoform_iframe_lang" value="ru">
        <input type="hidden" id="amoform_modal_button_color" value="#FFFFFF">
        <input type="hidden" id="amoform_modal_button_bg_color" value="#FF597C">
        <input type="hidden" id="amoform_modal_button_text" value="Заполнить форму">
        <input type="hidden" id="amoform_display" value="Y">
      <input id="visitor_uid" type="hidden" name="visitor_uid" value="11dbb633-d215-47a0-a040-44e26d93fac5"></form>