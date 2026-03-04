<?php
/**
 * Job dashboard (employer's jobs).
 *
 * @package Job_Connect
 * @var array $atts Shortcode attributes.
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	?>
	<div class="job-connect-require-login jc-content-wrap w-full my-6">
		<div class="job-connect-notice job-connect-notice--warning mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-amber-500 border-zinc-200 bg-amber-50 py-3 pl-4 pr-4" role="alert">
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-zinc-800">
				<?php esc_html_e( 'You must be logged in to view your job dashboard.', 'job-connect' ); ?>
			</p>
		</div>
		<div class="jc-auth-forms-row">
			<div class="jc-auth-form-block jc-auth-form-login">
				<?php echo do_shortcode( '[job_connect_login]' ); ?>
			</div>
			<?php if ( JC_Auth_Helpers::plugin_registration_enabled() ) : ?>
				<div class="jc-auth-form-block jc-auth-form-register">
					<?php echo do_shortcode( '[job_connect_register show_heading="1"]' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return;
}

$per_page   = isset( $atts['posts_per_page'] ) ? absint( $atts['posts_per_page'] ) : 25;
$paged      = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$search     = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
$args       = array(
	'post_type'      => JC_Post_Types::PT_LISTING,
	'author'         => get_current_user_id(),
	'posts_per_page' => $per_page,
	'paged'          => $paged,
	'post_status'    => array( 'publish', 'pending', 'draft', 'expired' ),
);
if ( $search !== '' ) {
	$args['s'] = $search;
}
$query = new WP_Query( $args );
$show_success_notice    = isset( $_GET['job_submitted'] ) && $_GET['job_submitted'] === '1';
$show_updated_notice    = isset( $_GET['job_updated'] ) && $_GET['job_updated'] === '1';
$show_marked_filled    = isset( $_GET['job_marked_filled'] ) && $_GET['job_marked_filled'] === '1';
$show_duplicated       = isset( $_GET['job_duplicated'] ) && $_GET['job_duplicated'] === '1';
$show_deleted          = isset( $_GET['job_deleted'] ) && $_GET['job_deleted'] === '1';
$show_error            = isset( $_GET['job_error'] ) ? sanitize_key( $_GET['job_error'] ) : '';
$form_errors           = JC_Form_Submit_Job::instance()->get_errors();
$show_submit_modal     = isset( $_POST['job_connect_submit'] ) && ! empty( $form_errors );
$dashboard_nonce       = JC_Dashboard_Actions::get_nonce();
$dashboard_base_url    = JC_Dashboard_Actions::get_dashboard_url();
?>
<div class="job-connect-dashboard jc-content-wrap w-full my-6">
	<?php if ( $show_success_notice ) : ?>
		<div class="job-connect-notice job-connect-notice--success mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-emerald-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-emerald-900"><?php esc_html_e( 'Your job was submitted successfully.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( $show_updated_notice ) : ?>
		<div class="job-connect-notice job-connect-notice--success mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-emerald-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-emerald-900"><?php esc_html_e( 'Your job was updated successfully.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( $show_marked_filled ) : ?>
		<div class="job-connect-notice job-connect-notice--success mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-emerald-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-emerald-900"><?php esc_html_e( 'Job marked as filled.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( $show_duplicated ) : ?>
		<div class="job-connect-notice job-connect-notice--success mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-emerald-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-emerald-900"><?php esc_html_e( 'Job duplicated. You can edit the new draft from this dashboard.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( $show_deleted ) : ?>
		<div class="job-connect-notice job-connect-notice--success mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-emerald-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-emerald-900"><?php esc_html_e( 'Job moved to trash.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( $show_error ) : ?>
		<div class="job-connect-notice job-connect-notice--warning mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-amber-500 bg-amber-50 py-3 pl-4 pr-4" role="alert">
			<span class="shrink-0 text-amber-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-amber-900"><?php echo esc_html( $show_error === 'nonce' ? __( 'Security check failed. Please try again.', 'job-connect' ) : ( $show_error === 'permission' ? __( 'You do not have permission to perform this action.', 'job-connect' ) : __( 'Something went wrong. Please try again.', 'job-connect' ) ) ); ?></p>
		</div>
	<?php endif; ?>

	<div class="jc-dashboard-intro mb-6 flex flex-wrap items-center justify-between gap-4">
		<form method="get" action="<?php echo esc_url( $dashboard_base_url ); ?>" class="jc-dashboard-search flex flex-1 min-w-0 max-w-md flex-wrap items-center gap-3 !mb-0">
			<label for="jc-dashboard-search" class="sr-only"><?php esc_html_e( 'Search your jobs', 'job-connect' ); ?></label>
			<div class="relative flex-1 min-w-0">
				<input type="search" id="jc-dashboard-search" name="search" class="block w-full rounded-md border border-zinc-300 py-2 px-3 text-sm text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="<?php esc_attr_e( 'Search', 'job-connect' ); ?>" value="<?php echo esc_attr( $search ); ?>" aria-label="<?php esc_attr_e( 'Search', 'job-connect' ); ?>">
			</div>
			<button type="submit" class="shrink-0 rounded-md border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"><?php esc_html_e( 'Search', 'job-connect' ); ?></button>
		</form>
		<div class="jc-dashboard-actions shrink-0">
			<button type="button" id="jc-open-submit-modal" class="jc-btn-primary inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" aria-haspopup="dialog" aria-expanded="false" aria-controls="jc-submit-job-dialog"><?php esc_html_e( 'Add Job', 'job-connect' ); ?></button>
		</div>
	</div>

	<div id="jc-submit-job-dialog" class="jc-dialog-overlay" role="dialog" aria-modal="true" aria-labelledby="jc-submit-dialog-title" aria-describedby="jc-submit-dialog-desc" aria-hidden="<?php echo $show_submit_modal ? 'false' : 'true'; ?>" data-open-on-load="<?php echo $show_submit_modal ? '1' : '0'; ?>">
		<div class="jc-dialog-backdrop" aria-hidden="true"></div>
		<div class="jc-dialog-panel" role="document">
			<h2 id="jc-submit-dialog-title" class="jc-dialog-title"><?php esc_html_e( 'Submit a Job', 'job-connect' ); ?></h2>
			<p id="jc-submit-dialog-desc" class="jc-dialog-description"><?php esc_html_e( 'Fill in the details below to submit a new job listing.', 'job-connect' ); ?></p>
			<div class="jc-dialog-body">
				<?php echo do_shortcode( '[submit_job_form]' ); ?>
			</div>
			<div class="jc-dialog-actions">
				<button type="button" class="jc-dialog-cancel" id="jc-close-submit-modal"><?php esc_html_e( 'Cancel', 'job-connect' ); ?></button>
				<button type="submit" form="job-connect-submit-job-form" class="jc-dialog-submit inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"><?php esc_html_e( 'Submit job', 'job-connect' ); ?></button>
			</div>
		</div>
	</div>
	<script>
	(function() {
		var dialog = document.getElementById('jc-submit-job-dialog');
		var openBtn = document.getElementById('jc-open-submit-modal');
		var closeBtn = document.getElementById('jc-close-submit-modal');
		var backdrop = dialog && dialog.querySelector('.jc-dialog-backdrop');
		function openModal() {
			if (!dialog) return;
			dialog.setAttribute('aria-hidden', 'false');
			dialog.classList.remove('hidden');
			document.body.style.overflow = 'hidden';
			if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
			// Refresh TinyMCE description editor when modal opens (it may have been hidden on load).
			setTimeout(function() {
				if (typeof tinymce !== 'undefined') {
					var ed = tinymce.get('job_description');
					if (ed) ed.fire('resize');
				}
			}, 100);
		}
		function closeModal() {
			if (!dialog) return;
			dialog.setAttribute('aria-hidden', 'true');
			dialog.classList.add('hidden');
			document.body.style.overflow = '';
			if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
		}
		if (openBtn) openBtn.addEventListener('click', openModal);
		if (closeBtn) closeBtn.addEventListener('click', closeModal);
		if (backdrop) backdrop.addEventListener('click', closeModal);
		if (dialog) {
			dialog.addEventListener('keydown', function(e) {
				if (e.key === 'Escape') closeModal();
			});
			dialog.addEventListener('click', function(e) {
				if (e.target === dialog) closeModal();
			});
			if (dialog.getAttribute('data-open-on-load') === '1') {
				dialog.classList.remove('hidden');
			} else {
				dialog.classList.add('hidden');
			}
		}
	})();
	</script>
	<?php if ( $query->have_posts() ) : ?>
		<div class="jc-dashboard-table-wrap overflow-x-auto text-left text-sm" role="region" aria-label="<?php esc_attr_e( 'Your job listings', 'job-connect' ); ?>">
			<div class="jc-dashboard-grid sm:min-w-[640px]" role="table">
				<div class="jc-dashboard-grid-header" role="row">
					<div class="jc-dashboard-th jc-col-job" role="columnheader"><?php esc_html_e( 'Title', 'job-connect' ); ?></div>
					<div class="jc-dashboard-th jc-col-date" role="columnheader"><?php esc_html_e( 'Date', 'job-connect' ); ?></div>
					<div class="jc-dashboard-th jc-col-location" role="columnheader"><?php esc_html_e( 'Location', 'job-connect' ); ?></div>
					<div class="jc-dashboard-th jc-col-salary" role="columnheader"><?php esc_html_e( 'Salary', 'job-connect' ); ?></div>
					<div class="jc-dashboard-th jc-col-views" role="columnheader"><?php esc_html_e( 'Views', 'job-connect' ); ?></div>
					<div class="jc-dashboard-th jc-col-actions" role="columnheader"><span class="sr-only"><?php esc_html_e( 'Actions', 'job-connect' ); ?></span></div>
				</div>
				<?php
				$status_labels = array(
					'publish' => __( 'Active', 'job-connect' ),
					'pending' => __( 'Pending', 'job-connect' ),
					'draft'   => __( 'Draft', 'job-connect' ),
					'expired' => __( 'Expired', 'job-connect' ),
				);
				while ( $query->have_posts() ) {
					$query->the_post();
					$id        = get_the_ID();
					$status    = get_post_status( $id );
					$posted    = get_the_date( '', $id );
					$expires   = get_post_meta( $id, '_job_expires', true );
					$is_filled = get_post_meta( $id, '_filled', true ) === '1';
					$location  = get_post_meta( $id, '_job_location', true );
					$salary    = get_post_meta( $id, '_job_salary', true );
					$views     = absint( get_post_meta( $id, '_view_count', true ) );
					$impressions = absint( get_post_meta( $id, '_impression_count', true ) );
					$can_edit  = JC_Form_Submit_Job::instance()->can_edit_job( $id );
					$status_badge = array(
						'publish' => 'bg-lime-400/20 text-lime-700',
						'pending' => 'bg-amber-400/20 text-amber-700',
						'draft'   => 'bg-zinc-500/10 text-zinc-700',
						'expired' => 'bg-red-400/20 text-red-700',
					);
					$badge_class = isset( $status_badge[ $status ] ) ? $status_badge[ $status ] : 'bg-zinc-500/10 text-zinc-700';
					$status_label = isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : ucfirst( $status );

					// Human-readable expiry.
					$expiry_text = '';
					if ( $expires ) {
						$expires_ts = strtotime( $expires );
						$now_ts     = current_time( 'timestamp' );
						if ( $expires_ts < $now_ts ) {
							$expiry_text = __( 'Expired', 'job-connect' );
						} else {
							$days = (int) floor( ( $expires_ts - $now_ts ) / DAY_IN_SECONDS );
							if ( $days === 0 ) {
								$expiry_text = __( 'Expires today', 'job-connect' );
							} elseif ( $days === 1 ) {
								$expiry_text = __( 'Expires tomorrow', 'job-connect' );
							} elseif ( $days < 30 ) {
								$expiry_text = sprintf( _n( 'Expires in %d day', 'Expires in %d days', $days, 'job-connect' ), $days );
							} elseif ( $days < 60 ) {
								$expiry_text = __( 'Expires in 1 month', 'job-connect' );
							} else {
								$months = (int) floor( $days / 30 );
								$expiry_text = sprintf( _n( 'Expires in %d month', 'Expires in %d months', $months, 'job-connect' ), $months );
							}
						}
					}

					$action_mark_filled_url = add_query_arg( array( 'action' => 'mark_filled', 'job_id' => $id, '_wpnonce' => $dashboard_nonce ), $dashboard_base_url );
					$action_edit_url        = add_query_arg( array( 'action' => 'edit', 'job_id' => $id, '_wpnonce' => $dashboard_nonce ), $dashboard_base_url );
					$action_duplicate_url   = add_query_arg( array( 'action' => 'duplicate', 'job_id' => $id, '_wpnonce' => $dashboard_nonce ), $dashboard_base_url );
					$action_delete_url      = add_query_arg( array( 'action' => 'delete', 'job_id' => $id, '_wpnonce' => $dashboard_nonce ), $dashboard_base_url );
					$company                = get_post_meta( $id, '_company_name', true );
					?>
					<div class="jc-dashboard-grid-row" role="row">
						<div class="jc-dashboard-row-inner">
						<div class="jc-dashboard-td jc-col-job" role="cell" data-label="<?php esc_attr_e( 'Title', 'job-connect' ); ?>">
							<div class="flex items-center gap-4">
								<div class="jc-dashboard-cell-icon flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500" aria-hidden="true">
									<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
								</div>
								<div class="min-w-0 flex-1">
									<div class="font-medium text-zinc-950">
										<a href="<?php echo esc_url( get_permalink( $id ) ); ?>" class="job-title hover:text-zinc-700 hover:underline" data-job-id="<?php echo esc_attr( (string) $id ); ?>"><?php the_title(); ?></a>
									</div>
									<div class="text-zinc-500">
										<?php if ( $company ) : ?>
											<span><?php echo esc_html( $company ); ?></span>
											<span class="jc-dashboard-cell-sep" aria-hidden="true"> · </span>
										<?php endif; ?>
										<span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xs font-medium <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $status_label ); ?></span>
									</div>
								</div>
							</div>
						</div>
						<div class="jc-dashboard-td jc-col-date" role="cell" data-label="<?php esc_attr_e( 'Date', 'job-connect' ); ?>">
							<div class="text-zinc-950"><?php echo esc_html( $posted ); ?></div>
							<?php if ( $expiry_text ) : ?>
								<div class="mt-0.5 text-zinc-500">
									<small><time datetime="<?php echo esc_attr( $expires ); ?>" title="<?php echo esc_attr( $expires ? date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) : '' ); ?>"><?php echo esc_html( $expiry_text ); ?></time></small>
								</div>
							<?php endif; ?>
						</div>
						<div class="jc-dashboard-td jc-col-location text-zinc-500" role="cell" data-label="<?php esc_attr_e( 'Location', 'job-connect' ); ?>">
							<?php echo $location ? esc_html( $location ) : '—'; ?>
						</div>
						<div class="jc-dashboard-td jc-col-salary text-zinc-500" role="cell" data-label="<?php esc_attr_e( 'Salary', 'job-connect' ); ?>">
							<?php echo $salary ? esc_html( $salary ) : '—'; ?>
						</div>
						<div class="jc-dashboard-td jc-col-views" role="cell" data-label="<?php esc_attr_e( 'Views', 'job-connect' ); ?>">
							<div class="text-zinc-950"><?php echo esc_html( sprintf( _n( '%d view', '%d views', $views, 'job-connect' ), $views ) ); ?></div>
							<div class="mt-0.5 text-zinc-500"><small><?php echo esc_html( sprintf( _n( '%d impression', '%d impressions', $impressions, 'job-connect' ), $impressions ) ); ?></small></div>
						</div>
						<div class="jc-dashboard-td jc-col-actions" role="cell" data-label="<?php esc_attr_e( 'Actions', 'job-connect' ); ?>">
							<div class="jc-dashboard-job-actions flex flex-wrap items-center gap-2">
								<details class="jc-actions-menu relative inline-block" onfocusout="event.currentTarget.contains(event.relatedTarget) || setTimeout(function(){ this.open = false; }.bind(this), 100)">
									<summary tabindex="0" class="jc-actions-menu-trigger inline-flex h-8 w-8 cursor-pointer list-none items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-500 shadow-sm hover:bg-zinc-50 hover:text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 [&::-webkit-details-marker]:hidden" aria-label="<?php esc_attr_e( 'Actions', 'job-connect' ); ?>">
										<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
									</summary>
									<div class="jc-actions-menu-content absolute right-0 top-full z-10 mt-1 min-w-[140px] rounded-md border border-zinc-200 bg-white py-1 shadow-lg">
										<?php if ( $can_edit ) : ?>
											<a href="<?php echo esc_url( $action_edit_url ); ?>" class="jc-dashboard-action block w-full px-4 py-2 text-left text-sm text-zinc-700 hover:bg-zinc-100"><?php esc_html_e( 'Edit', 'job-connect' ); ?></a>
										<?php endif; ?>
										<?php if ( ! $is_filled && ( $status === 'publish' || $status === 'pending' ) ) : ?>
											<a href="<?php echo esc_url( $action_mark_filled_url ); ?>" class="jc-dashboard-action block w-full px-4 py-2 text-left text-sm text-zinc-700 hover:bg-zinc-100"><?php esc_html_e( 'Mark filled', 'job-connect' ); ?></a>
										<?php endif; ?>
										<a href="<?php echo esc_url( $action_duplicate_url ); ?>" class="jc-dashboard-action block w-full px-4 py-2 text-left text-sm text-zinc-700 hover:bg-zinc-100"><?php esc_html_e( 'Duplicate', 'job-connect' ); ?></a>
										<a href="<?php echo esc_url( $action_delete_url ); ?>" class="jc-dashboard-action jc-dashboard-action-delete block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50"><?php esc_html_e( 'Delete', 'job-connect' ); ?></a>
									</div>
								</details>
								<div class="jc-dashboard-actions-inline" aria-label="<?php esc_attr_e( 'Actions', 'job-connect' ); ?>">
									<?php if ( $can_edit ) : ?>
										<a href="<?php echo esc_url( $action_edit_url ); ?>" class="jc-dashboard-action jc-dashboard-action-inline"><?php esc_html_e( 'Edit', 'job-connect' ); ?></a>
									<?php endif; ?>
									<?php if ( ! $is_filled && ( $status === 'publish' || $status === 'pending' ) ) : ?>
										<a href="<?php echo esc_url( $action_mark_filled_url ); ?>" class="jc-dashboard-action jc-dashboard-action-inline"><?php esc_html_e( 'Mark filled', 'job-connect' ); ?></a>
									<?php endif; ?>
									<a href="<?php echo esc_url( $action_duplicate_url ); ?>" class="jc-dashboard-action jc-dashboard-action-inline"><?php esc_html_e( 'Duplicate', 'job-connect' ); ?></a>
									<a href="<?php echo esc_url( $action_delete_url ); ?>" class="jc-dashboard-action jc-dashboard-action-inline jc-dashboard-action-delete"><?php esc_html_e( 'Delete', 'job-connect' ); ?></a>
								</div>
							</div>
						</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php wp_reset_postdata(); ?>
		<?php if ( $query->max_num_pages > 1 ) : ?>
			<?php
			$paginate_args = array( 'total' => $query->max_num_pages );
			if ( $search !== '' ) {
				$paginate_args['add_args'] = array( 'search' => $search );
			}
			?>
			<nav class="job-connect-pagination mt-6 text-center" aria-label="<?php esc_attr_e( 'Job list pagination', 'job-connect' ); ?>"><?php echo wp_kses_post( paginate_links( $paginate_args ) ); ?></nav>
		<?php endif; ?>
	<?php else : ?>
		<p class="text-sm text-zinc-600"><?php esc_html_e( 'You have not submitted any jobs yet.', 'job-connect' ); ?></p>
	<?php endif; ?>
</div>
