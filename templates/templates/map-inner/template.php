<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * $arResult
 * $arParams
 */

$ajaxPath = $this->GetFolder() . "/ajax.php";

$mainId = $this->GetEditAreaId($arParams["NAME"]);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
?>

<div class="map-inner block-shadow">
	<form class="shake_btn_parsley_form" data-parsley-validate="" id="<?=$obName?>">
		<h4>Напишите нам</h4>
		<span class="modal-span__input">
			Представьтесь
		</span>
		<div class="txt-input__double">
			<label class="txt-input">
				<div class="txt-input__wrap">
					<input
						type="text"
						class="txt-input__field"
						data-parsley-required="true"
						data-parsley-required-message="Введите имя"
						data-parsley-trigger="change click" 
						placeholder="Имя"
						name="NAME"
					/>
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
						placeholder="Телефон"
						name="PHONE"
					/>
					<div class="placeholder-input">Хороший номер телефона</div>
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
						placeholder="E-mail*"
						name="EMAIL"
					/>
					<div class="placeholder-input">Прекрасный E-mail</div>
					<a href="#" class="input-clear"></a>
				</div>
			</label>
		</div>
		<label class="txt-input textarea-input">
			<div class="txt-input__wrap">
				<textarea
					class="txt-input__textarea txt-input__field"
					type="text"
					name="MESSAGE"
				></textarea>
				<div class="placeholder">Сообщение</div>
				<a href="#" class="input-clear"></a>
			</div>
		</label>
		<div class="box_btn_form call_btn_box">
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
							Я принимаю условия политики конфиденциальности
						</a>
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
			return false;
		});
	})(window,$)
</script>