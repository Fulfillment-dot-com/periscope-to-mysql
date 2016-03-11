<?php  namespace Fulfillment\periscopeToSQL;

class periscopeToSQL {

	public static function fillTemplate($template, $fields) {

		if (preg_match('/\[([a-z\_]+)\:est\]/i', $template, $matches)) {
			$replacement = '(date_add(' . $matches[1] . ', interval -5 hour))';
			$replace     = '[' . $matches[1] . ':est]';
			$template    = str_replace($replace, $replacement, $template);
		}

		if (preg_match('/\[([a-z\_]+)\=daterange(:est)?\]/i', $template, $matches)) {

			$start = abs(strtotime($fields['dateStart']) + (60 * 60 * 5) - time());
			$end   = abs(strtotime($fields['dateEnd']) + (60 * 60 * 5) - time());
			$start = floor($start / (60 * 60 * 24));
			$end   = floor($end / (60 * 60 * 24)) - 1; // minus 1 for end-of-day

			$startSql = $endSql = 'DATE_ADD(DATE(now()), interval 5 hour)';

			if ($start) {
				$startSql = 'DATE_SUB(' . $startSql . ', interval ' . $start . ' day)';
			}
			if ($end) {
				$endSql = 'DATE_SUB(' . $endSql . ', interval ' . $end . ' day)';
			}

			$replacement = $matches[1] . ' >= ' . $startSql . ' AND ' . $matches[1] . ' < ' . $endSql;

			$replace  = [
				'[' . $matches[1] . '=daterange:est]',
				'[' . $matches[1] . '=daterange]',
			];
			$template = str_replace($replace, $replacement, $template);
		}

		foreach ($fields as $key => $value) {

			if (preg_match('/\[(.*)=' . $key . '\]/', $template, $matches)) {

				if (!$value) {
					$replacement = '1=1';
				} else {
					$replacement = $matches[1];

					if (periscopeToSQL::isRepeating($value)) {
						$replacement .= ' IN (' . $value . ')';
					} else {
						$replacement .= ' = ' . $value;
					}
				}
				$replace  = '[' . $matches[1] . '=' . $key . ']';
				$template = str_replace($replace, $replacement, $template);
			}
		}

		return $template;
	}

	public static function isRepeating($value) {

		if (preg_match('/^[0-9,]+,[0-9]+$/', $value)) {
			return true;
		}
		if (preg_match('/^("(.*)",)*"(.*)"$/', $value)) {
			return true;
		}

		return false;
	}

}