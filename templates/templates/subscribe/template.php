<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * $arResult
 * $arParams
 */

$ajaxPath = $this->GetFolder() . "/ajax.php";

$mainId = $this->GetEditAreaId($arParams["NAME"]);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
?>

<div class="form-red">
	<div class="form-red__img"></div>
	<p class="_primary-text-left"><span class="form-heading">Подпишитесь на нашу рассылку</span> <span class="form-red__small">И первыми узнавайте о самых выгодных предложениях</span></p>
	<div class="sp-form-outer sp-force-hide">
    <div id="sp-form-106013" sp-id="106013" sp-hash="8b224401b54ecc4c25465166fe6cec4ccb53607912dbe177b186f3a26924b81b" sp-lang="ru" class="sp-form sp-form-regular sp-form-embed" sp-show-options="%7B%22satellite%22%3Afalse%2C%22maDomain%22%3A%22login.sendpulse.com%22%2C%22formsDomain%22%3A%22forms.sendpulse.com%22%2C%22condition%22%3A%22onEnter%22%2C%22scrollTo%22%3A25%2C%22delay%22%3A10%2C%22repeat%22%3A3%2C%22background%22%3A%22rgba(0%2C%200%2C%200%2C%200.5)%22%2C%22position%22%3A%22bottom-right%22%2C%22animation%22%3A%22%22%2C%22hideOnMobile%22%3Afalse%2C%22urlFilter%22%3Afalse%2C%22urlFilterConditions%22%3A%5B%7B%22force%22%3A%22hide%22%2C%22clause%22%3A%22contains%22%2C%22token%22%3A%22%22%7D%5D%7D">
        <div class="sp-form-fields-wrapper">
            <div class="sp-message">
                <div></div>
            </div>
	<form id="<?=$obName?>" class="shake_btn_parsley_form sp-element-container ui-sortable ui-droppable" data-parsley-validate="" novalidate="">
		<label class="txt-input">
			<div class="txt-input__wrap txt-input__wrap-secondary" sp-id="sp-2423c0b3-1309-4331-8d96-e8ace9483aa4">
					<input
						type="email"
						class="txt-input__field txt-input__secondary sp-form-control"
						sp-type="email"
						data-parsley-required="true"
						data-parsley-required-message="Введите E-mail"
						data-parsley-type-message="Некорректный E-mail"
						data-parsley-trigger="change"
						data-parsley-minlength="8"
						data-parsley-minlength-message="Минимальная длинна 8 символов"
						sp-tips="%7B%22required%22%3A%22%D0%9E%D0%B1%D1%8F%D0%B7%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D0%B5%20%D0%BF%D0%BE%D0%BB%D0%B5%22%2C%22wrong%22%3A%22%D0%9D%D0%B5%D0%B2%D0%B5%D1%80%D0%BD%D1%8B%D0%B9%20email-%D0%B0%D0%B4%D1%80%D0%B5%D1%81%22%7D"
						data- required="required"
						name="sform[email]" 
					/>
					<div class="placeholder">Ваш E-mail</div>
					<div class="placeholder-input">Хороший e-mail</div>
					<a href="#" class="input-clear"></a>
            </div>
		</label>
		<div class="box_btn_form call_btn_box">
			<div class="sp-field sp-button-container" sp-id="sp-ace5d9ce-639c-4ebf-b6e9-93331a320ff7">
                <button type="submit" 
                		id="sp-ace5d9ce-639c-4ebf-b6e9-93331a320ff7" 
                		class="btn btn-white sp-button">
                		Отправить
                </button>
            </div>
		</div>

		<div class="txt-input__wrap form-red__cb">
			<label class="cb-input">
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
				<span class="cb-input__label form-privacy-text">
					Я принимаю условия
					<a data-fancybox="" data-src="#policy" href="javascript:;">
						политики конфиденциальности
					</a>
				</span>
			</label>
		</div>
	</form>

	<script type="text/javascript" src="//static-login.sendpulse.com/apps/fc3/build/default-handler.js?1535640929611"></script>
        </div>
    </div>
</div>
</div>
<script>
	(function(){
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
							FROM_PAGE: window.location.pathname
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
								window.yaCounter<?=$arParams["YA_COUNTER"]?>.reachGoal('<?=$arParams["YA_GOAL"]?>')
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
			});
			return false;
		});
	})()
</script>