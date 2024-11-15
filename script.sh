#!/bin/bash
(crontab -l | grep -v "https://eg-admin.twissta.com/ /home/u105936410/domains/twissta.com/public_html/eg-admin/artisan dm:disbursement") | crontab -
(crontab -l ; echo "01 04 * * * https://eg-admin.twissta.com/ /home/u105936410/domains/twissta.com/public_html/eg-admin/artisan dm:disbursement") | crontab -
(crontab -l | grep -v "https://eg-admin.twissta.com/ /home/u105936410/domains/twissta.com/public_html/eg-admin/artisan store:disbursement") | crontab -
(crontab -l ; echo "00 05 * * * https://eg-admin.twissta.com/ /home/u105936410/domains/twissta.com/public_html/eg-admin/artisan store:disbursement") | crontab -
