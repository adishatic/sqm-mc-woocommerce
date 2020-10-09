<?php

if ( ! class_exists( 'Squalomail_Woocommerce_Job' ) ) {
	abstract class Squalomail_Woocommerce_Job {

		private $attempts = 0;

		/**
		 * Set attempts
		*/
		public function set_attempts( $attempts ) {
			$this->attempts = $attempts;
		}

		/**
		 * Get attempts
		*/
		public function get_attempts( ) {
			return $this->attempts;
		}

		/**
		 * Reschedule action 4 times
		*/
		public function retry( $delay = 30 ) {
			$job = $this;
			if (null == $job->attempts) $job->set_attempts(0);
			$job->set_attempts($job->get_attempts() + 1);
			squalomail_as_push($job, $delay);
		}

		/**
		 * @return $this
		 */
		protected function applyRateLimitedScenario()
		{
			squalomail_set_transient('api-rate-limited', true, 60);

			$this->retry();

			return $this;
		}
		
		/**
		 * Handle the job.
		 */
		abstract public function handle();

	}
}
