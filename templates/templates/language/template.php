<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * $arResult
 * $arParams
 */

$ajaxPath = $this->GetFolder() . "/ajax.php";

$mainId = $this->GetEditAreaId($arParams["NAME"]);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
?>

<div id="language" class="popup_header_callback call-back__modal popup-small popup__img">
	<div class="fancy">
		<div class="fancy-inner__img"></div>
		<div class="form_close"><a href="javascript:;" class="form_close__btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"><path fill="#8A8A8A" d="M16 13.934L29.934 0 32 2.066 18.066 16 32 29.934 29.934 32 16 18.066 2.066 32 0 29.934 13.934 16 0 2.066 2.066 0 16 13.934z"/></svg>
		</a></div>
		<div class="fancy-inner">
			<h2 class="_H-h2-left js-hide">Подтянуть язык</h2>
			<div class="modal-order__visible">
				<form class="call_form shake_btn_parsley_form" data-parsley-validate="" id="<?=$obName?>">
					<div class="list-language">
						<ul>
							<li>
								<p>Язык</p>
								<input type="hidden" name="COUSE" value="Английский" />
								<span class="js_language_language">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/eng.png" alt="">
									Английский
								</span>
							</li>
							<li>
								<p>Уровень</p>
								<input type="hidden" name="LVL" value="B1, средний (пороговый)" />
								<span class="js_language_lvl">
									B1, средний (пороговый)
								</span>
							</li>
						</ul>
					</div>
					<label class="txt-input">
						<div class="txt-input__wrap">
							<input
								type="text"
								class="txt-input__field"
								data-parsley-required="true"
								data-parsley-required-message="Введите имя"
								data-parsley-trigger="change click" 
								name="NAME"
							/>
							<div class="placeholder">Ваше имя</div>
							<div class="placeholder-input">Хорошее имя</div>
							<a href="#" class="input-clear"></a>
						</div>
					</label>
					<label class="txt-input">
						<div class="txt-input__wrap">
							<input
								type="text"
								class="txt-input__field phone"
								data-parsley-required="true"
								data-parsley-required-message="Введите номер телефона"
								data-parsley-trigger="change"
								name="PHONE"
							/>
							<div class="placeholder">Телефон *</div>
							<div class="placeholder-input">Хороший номер телефона</div>
							<a href="#" class="input-clear"></a>
						</div>
					</label>
					<div class="box_btn_form call_btn_box">
						<button class="btn btn-primary" type="submit">
							<span>Записаться</span>
						</button>
						<div class="txt-input__wrap">
							<label class="cb-input cb-input__inverse">
								<input
									class="cb-input__checkbox"
									type="checkbox"
									name="policy_accept"
									checked
									required
									data-parsley-checkmin="1"
									data-parsley-required="true"
									data-parsley-required-message="Необходимо принять"
								/>
								<span class="cb-input__check"></span>
								<span class="cb-input__label">
									<a data-fancybox="" data-src="#policy" href="javascript:;">
										Я принимаю условия политики конфиденциальности
									</a>
								</span>
							</label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-order__success">
				<h2 class="_H-h2-left">Ваша заявка принята</h2>
				<p class="modal-succcess__text">Наш менеджер свяжется с вами в ближайшее время</p>
				<div class="box_btn_form call_btn_box">
					<a href="javascript:;" class="btn btn-transparent form_close__btn">Ок, жду</a>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	(function(w,$){
		var callbackForm = $('#<?=$obName?>');

		callbackForm.on('submit', function(e){
			e.preventDefault()

			//Здесь аякс
			var to_data = {
				// id: 123,
				sessid: BX.bitrix_sessid(),
				action: "callback",
				signedParamsString: "<?=CUtil::JSEscape($arResult["SIGNER"])?>",
				parametrs: callbackForm
					.serializeArray()
					.reduce(
						function (acc,e) {
							acc[e.name] = e.value
							return acc
						},
						{
							FROM_PAGE: w.location.pathname
						}
					)
			};
			if ($(this).parsley().isValid() ) {
			var target = $(this);
			$.ajax({
				url: '<?=$ajaxPath?>',
				type: 'POST',
				dataType: 'json',
				data: to_data,
				success: function(data) {
					if (!!data["Errors"] && data["Errors"].length == 0) {
						//Успешный аякс
						callbackForm[0].reset();

						<? if (strlen($arParams["YA_GOAL"]) && $arParams["YA_COUNTER"] > 0): ?>
							try {
								w.yaCounter<?=$arParams["YA_COUNTER"]?>.reachGoal('<?=$arParams["YA_GOAL"]?>')
							} catch(error) {
								alert("ya metrika error! Проверьте введенные параметры");
								console.error(error);
							}
						<? endif; ?>

						target.parents('.modal-order__visible').siblings('.modal-order__success').delay(300).fadeIn(300);
						target.parents('.modal-order__visible').siblings('.js-hide').fadeOut(300);
						target.parents('.modal-order__visible').fadeOut(300);
						target.parents('.popup_header_callback').addClass('popup_success');

					} else {
						var error;
						for (error in data["Errors"]) {
							console.log(data["Errors"][error]);
						}
					}
				}
			}).always(function() {
				setTimeout(
					function() {$.fancybox.close()},
					300
				);
			});
			}
			return false;
		});
	})(window,$)
</script>