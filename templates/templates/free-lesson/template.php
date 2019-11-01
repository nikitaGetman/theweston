<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * $arResult
 * $arParams
 */

$ajaxPath = $this->GetFolder() . "/ajax.php";

$mainId = $this->GetEditAreaId($arParams["NAME"]);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
?>

<div class="modal-lesson">
	<span class="_primary-text-left modal-span__input">Ваши данные:</span>
	<div class="modal-lesson__img"></div>
	<form class="shake_btn_parsley_form" data-parsley-validate="" id="<?=$obName?>">
		<div class="txt-input__double">
			<label class="txt-input">
				<div class="txt-input__wrap">
					<input
						type="text"
						class="txt-input__field"
						data-parsley-required="true"
						data-parsley-required-message="Введите имя"
						data-parsley-trigger="change click" 
						name="NAME"
						placeholder="Имя"
					/>
					<div class="placeholder-input">Хорошее имя</div>
					<a href="#" class="input-clear"></a>
				</div>
			</label>
			<label class="txt-input">
				<div class="txt-input__wrap">
					<input
						type="email"
						class="txt-input__field"
						data-parsley-required="true"
						data-parsley-required-message="Введите E-mail"
						data-parsley-type-message="Некорректный E-mail"
						data-parsley-trigger="change click"
						data-parsley-minlength="8"
						data-parsley-minlength-message="Минимальная длинна 8 символов"
						name="EMAIL"
						placeholder="E-mail*"
					/>
					<div class="placeholder-input">Прекрасный E-mail</div>
					<a href="#" class="input-clear"></a>
				</div>
			</label>
		</div>
		<div class="box_btn_form call_btn_box double-box">
			<button class="btn btn-primary" id="js-feedback-hide" type="submit">
				<span>Отправить</span>
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
						Я принимаю условия политики конфиденциальности</a>
					</span>
				</label>
			</div>
		</div>
	</form>
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
					function() {},
					300
				);
			});
			}
			return false;
		});
	})(window,$)
</script>