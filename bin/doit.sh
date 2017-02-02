echo "Generate"
date
rm create500.sql
php createcase.php ws500kDave 1000 499000 > create500.sql
echo "Execute"
date
mysql -h benchmark-db-2.processmaker.net -u RDSQAMaster --password=aQ2Qm63samfbrngC wf_ws500kDave < create500.sql
