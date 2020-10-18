<?php
/**
 * CDEK client
 *
 * @package CDEK/Client
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Client API connection
 *
 * @class CDEKFW_Client
 */
class CDEKFW_Promo {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'admin_promo_notice' ) );
	}

	/**
	 * Promo notice
	 */
	public function admin_promo_notice() {
		if ( CDEKFW::is_pro_active() && class_exists( 'RPAEFW' ) ) {
			return;
		}

		if ( isset( $_REQUEST['tab'] ) &&
			 $_REQUEST['tab'] === 'shipping' &&
			 isset( $_REQUEST['page'] ) &&
			 $_REQUEST['page'] === 'wc-settings' ) {
			if ( isset( $_REQUEST['section'] ) && 'cdek' === $_REQUEST['section'] ) {

			} else {
				$instance_id = 0;
				if ( isset( $_REQUEST['instance_id'] ) ) {
					$instance_id = intval( $_REQUEST['instance_id'] );
				}

				if ( ! $instance_id ) {
					return;
				}

				if ( ! class_exists( 'WC_Shipping_Zone_Data_Store' ) ) {
					return;
				}

				$WC_Shipping_Zone_Data_Store = new WC_Shipping_Zone_Data_Store();
				$shipping_type               = $WC_Shipping_Zone_Data_Store->get_method( $instance_id );

				if ( 'cdek_shipping' !== $shipping_type->method_id ) {
					return;
				}
			}
		} else {
			return;
		}

		?>
		<div id="cdekfw-promo">
			<?php if ( ! class_exists( 'RPAEFW' ) ) : ?>
				<div class="cdekfw-promo-block">
					<h3 class="wc-settings-sub-title">
						Почта России и PRO расширение
					</h3>
					<p>
						Бесплатный плагин для расчета стоимости и сроков доставки доступен на официальном
						<a href="https://ru.wordpress.org/plugins/russian-post-and-ems-for-woocommerce/" target="_blank">
							репозитории плагинов WordPress
						</a>
					</p>
					<p>
						Плюс коммерческое PRO расширение с поддержка отправлений для <b>корпоративных клиентов</b> Почты РФ включая ЕКОМ, а так же синхронизацию заказов с <b>личным кабинетом</b> для автоматического заполнения бланков, создания партий и ускоренного приема отправлений в отделении.
					</p>
					<br>
					<a href="https://yumecommerce.com/pochta/" target="_blank" class="button-primary">Посмотреть демо</a>
					<a href="https://woocommerce.com/products/russian-post-and-ems-pro-for-woocommerce/" target="_blank" class="button">Купить</a>
					<br>
					<small style="margin-top: 10px">
						Для использования функций личного кабинета и ЕКОМ требуется активный договор с АО «Почта России» для интернет-магазинов.
					</small>
				</div>
			<?php endif; ?>
			<?php if ( ! CDEKFW::is_pro_active() ) : ?>
				<div class="cdekfw-promo-block">
					<h3 class="wc-settings-sub-title">СДЭК PRO для WooCommerce</h3>
					<p>
						Передача информации по заказу в систему СДЭК плюс формирование печатной формы квитанции к заказу
						и
						ШК-места.
					</p>
					Так же PRO дополнение включает:
					<ul class="ul-disc">
						<li>
							База областей и городов РФ для простого поиска и выбора.
							<img src="<?php echo CDEK_PLUGIN_DIR_URL . 'assets/images/state-city-select.png'; ?>"
								 style="max-width: 240px">
							<small>Включает 50+ тысяч адресов официального справочника.</small>
						</li>
						<li>
							Автопоиск индекса для области/города.
							<small>Индекс больше не является обязательным полем.</small>
						</li>
						<li>
							Получение информации о заказе, в том числе о текущем статусе.

							<small>
								Отслеживание заказа в админ панели.
							</small>
						</li>
						<li>
							Синхронизация и отображение пунктов СДЭК на карте.
							<img src="<?php echo CDEK_PLUGIN_DIR_URL . 'assets/images/pvz-select.png'; ?>" alt="">
							<small>Отображение пунктов выдачи заказа в городе покупателя с возможностью выбора на
								карте.</small>
						</li>
						<li>
							Автоматическое получение и отправка трек номера.
							<small>
								Так же возможно синхронизировать заказ с трекингом для автоматической перевода статуса
								заказа в
								завершенные после получения отправления покупателем.
							</small>
						</li>
						<li>
							Синхронизация заказов с личным кабинетом в один клик.
							<img src="<?php echo CDEK_PLUGIN_DIR_URL . 'assets/images/order.png'; ?>"
								 style="max-width: 200px">
							<small>Возможность автоматизировать отправку заказов при определенном статусе
								заказа.</small>
						</li>
						<li>
							Опции для создания бесплатной доставки.
						</li>
						<li>
							Поддержка заказов с наложенным платежом.
						</li>
						<li>
							Возможность создания списка дополнительных альтернативных тарифов внутри одного метода.
						</li>
						<li>
							Регистрация заявки на вызов курьера
							<img src="<?php echo CDEK_PLUGIN_DIR_URL . 'assets/images/intakes.png'; ?>"
								 style="max-width: 200px">
							<small>Возможность назначать дату и время ожидания курьера.</small>
						</li>
						<li>
							Дополнительные опции для работы с классами доставки и общими параметрами метода доставки.
						</li>
						<li>
							Возможность использования разных адресов отправки для тарифов дверь-склад и дверь-дверь.
							<small>Помогает использовать метод для определенных зон доставки к которой относится склад.</small>
						</li>
						<br>
						<a href="https://yumecommerce.com/cdek/" target="_blank" class="button-primary">Посмотреть
							демо</a>
						<a href="https://woocommerce.com/products/cdek-pro-for-woocommerce/" target="_blank"
						   class="button">Купить</a>
						<br>
						<small style="margin-top: 10px">
							Для использования функций интеграции личного кабинета требуется активный договор со СДЭК.
						</small>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<style>
			#mainform {
				position: relative;
			}

			#cdekfw-promo {
				position: absolute;
				z-index: 999;
				top: 130px;
				right: 0;
				width: 370px;
			}

			#cdekfw-promo .cdekfw-promo-block {
				border: 1px solid #7e8993;
				background: #fff;
				padding: 20px 30px 30px;
				border-radius: 3px;
				margin-bottom: 50px;
			}

			#cdekfw-promo img {
				margin-top: 10px;
			}

			#cdekfw-promo small {
				opacity: .8;
				line-height: 1.5;
				padding: 5px 0 0 0;
				display: block;
			}

			#cdekfw-promo li {
				margin-bottom: 14px !important;
			}

			#cdekfw-promo img {
				width: 100%;
				display: block;
			}

			#wpbody-content form > *:not(.woo-nav-tab-wrapper) {
				max-width: calc(100% - 430px);
			}
		</style>

		<script>
			(function () {
				let promo = document.getElementById('cdekfw-promo');
				let form = document.getElementById('mainform');

				if (promo && form) {
					form.appendChild(promo)
				}
			})()
		</script>
		<?php
	}
}

new CDEKFW_Promo();
