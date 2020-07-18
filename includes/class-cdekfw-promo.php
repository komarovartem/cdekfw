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
		if ( CDEKFW::is_pro_active() ) {
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
		<div id="rpaefw-promo">
			<h3 class="wc-settings-sub-title">СДЭК PRO</h3>
			<p>
				Передача информации по заказу в систему СДЭК и формирование печатной формы квитанции к заказу и печать
				ШК-места.
			</p>
			Так же PRO дополнение включает:
			<ul class="ul-disc">
				<li>
					База областей и городов РФ для простого поиска и выбора.
					<img src="<?php echo CDEK_PLUGIN_DIR_URL . 'assets/images/state-city-select.png'; ?>"
						 style="max-width: 240px">
					<small>Включает 50+ тысяч адресов официального справочника Почты РФ</small>
				</li>
				<li>
					Автопоиск индекса для области/города.
					<small>Индекс больше не является обязательным полем</small>
				</li>
				<li>
					Получение информации о заказе, в том числе о текущем статусе

					<small>
						Отслеживание заказа в админ панели.
					</small>
				</li>
				<li>
					Синхронизация и отображение <b>пунктов СДЭК на карте</b>
					<img src="<?php echo CDEK_PLUGIN_DIR_URL . 'assets/images/pvz-select.png'; ?>" alt="">
					<small>Отображение пунктов выдачи заказа в городе покупателя с возможностью выбора на карте</small>
				</li>
				<li>
					Автоматическое получение и отправка трек номера.
					<small>
						Так же возможно синхронизировать заказ с трекингом для автоматической перевода статуса заказа в
						завершенные после получения отправления покупателем
					</small>
				</li>
				<li>
					Синхронизация заказов с личным кабинетом в один клик.
					<img src="<?php echo CDEK_PLUGIN_DIR_URL . 'assets/images/order.png'; ?>"
						 style="max-width: 200px">
					<small>Возможность автоматизировать отправку заказов при определенном статусе заказа.</small>
				</li>
				<li>
					Опции для создания беспатной доставки.
				</li>
				<li>
					Поддержка заказов с наложенным платежом.
				</li>
				<li>
					Дополнительные опции для работы с классами доставки и общими параметрами метода доставки.
				</li>
				<br>
				<a href="https://yumecommerce.com/cdek/" target="_blank" class="button-primary">Посмотреть демо</a>
				<a href="https://woocommerce.com/products/cdek-pro-for-woocommerce/" target="_blank"
				   class="button">Купить</a>
				<br>
				<small style="margin-top: 10px">
					Для использования функций интеграции личного кабинета требуется активный договор со СДЭК.
				</small>
			</ul>
		</div>

		<style>
			#mainform {
				position: relative;
			}

			#rpaefw-promo {
				position: absolute;
				right: 20px;
				z-index: 999;
				top: 130px;
				right: 0;
				width: 300px;
				border: 1px solid #7e8993;
				background: #fff;
				padding: 30px;
				border-radius: 3px;
			}

			#rpaefw-promo img {
				margin-top: 10px;
			}

			#rpaefw-promo small {
				opacity: .8;
				line-height: 1.5;
				padding: 5px 0 0 0;
				display: block;
			}

			#rpaefw-promo li {
				margin-bottom: 14px !important;
			}

			#rpaefw-promo img {
				width: 100%;
				display: block;
			}

			#wpbody-content form > *:not(.woo-nav-tab-wrapper) {
				max-width: calc(100% - 430px);
			}
		</style>

		<script>
			(function () {
				let promo = document.getElementById('rpaefw-promo');
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
