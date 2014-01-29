<?php
class AdvancedEmailLogRoute extends CEmailLogRoute {
        protected function processLogs($logs) {
                if (empty($logs)) {
                        return;
                }
                parent::processLogs($logs);
        }
}