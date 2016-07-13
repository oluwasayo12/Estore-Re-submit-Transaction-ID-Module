<?php
/**
* Helper class for Hello World! module
* 
* @package    Joomla.Tutorials
* @subpackage Modules
* @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
* @license        GNU/GPL, see LICENSE.php
* mod_helloworld is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.*/
// no direct access

class ModVoguepayHelper{
/**
* Retrieves the hello message
*
* @param   array  $params An object containing the module parameters
*
* @access public
*/   
    public static function getMerchantid($params){


        if(isset($_POST['submit'])){
        $transaction_id = $_POST['transaction_id'];

        $xml = file_get_contents('https://voguepay.com/?v_transaction_id='.$_POST['transaction_id'].'&type=xml&demo=true');

        $xml_elements = new SimpleXMLElement($xml);
        $transaction = array();
        $t = array();
        foreach($xml_elements as $key => $value) 
        {
        $transaction[$key]=$value;
        }
        $merchant_id = $transaction['merchant_id']; 
        $transaction_id = $transaction['transaction_id'];
        $merchant_ref = $transaction['merchant_ref'];
        $status = $transaction['status'];
        //return  $status;
            if(trim(strtolower($status)) == 'approved'  ){

                $db = JFactory::getDbo();
                $query = $db->getQuery(true); 
                $columns = array('tx_user_id', 'tx_unit', 'tx_status' );
                $query->select($columns)
                ->from($db->quoteName('#__spc_transactions')) 
                ->where($db->quoteName('tx_id').'='.$merchant_ref); 
                $db->setQuery($query); 
                $result = $db->loadObject();

                $tx_user_id = $result->tx_user_id;
                $tx_unit = $result->tx_unit;
                $tx_status = $result->tx_status;

                if ($tx_status === 'Approved') {
                    return "Your transaction has already been approved";
                }
                elseif($tx_status === 'Pending'){
                    $query = $db->getQuery(true); 
                    $columns = array('user_id', 'wallet' );
                    $query->select($columns)
                    ->from($db->quoteName('#__users_xtra')) 
                    ->where($db->quoteName('user_id').'='.$tx_user_id); 
                    $db->setQuery($query); 
                    $result = $db->loadObject();


                    $wallet = $result->wallet;
                    // //adding the unit (old + NEW)
                    $newbalance= $wallet + $tx_unit;
                    //Updating the users_xtra table
                    $query = $db->getQuery(true);
                    // Fields to update.
                    $fields = $db->quoteName('wallet') . ' = '.$newbalance;
                    // Conditions for which records should be updated.
                    $conditions = array(
                    $db->quoteName('user_id') . ' = '. $tx_user_id 
                    );

                    $query->update($db->quoteName('#__users_xtra'))->set($fields)->where($conditions);

                    $db->setQuery($query);

                    $result = $db->execute();

                    if ($result < 1) {
                        return "account not yet updated ";
                    }

                    else{
                        $db = JFactory::getDbo();

                        $query = $db->getQuery(true); 
                        $columns = array('tx_user_id', 'tx_unit', 'tx_status' );
                        $query->select($columns)
                        ->from($db->quoteName('#__spc_transactions')) 
                        ->where($db->quoteName('tx_id').'='.$merchant_ref); 
                        $db->setQuery($query); 
                        $result = $db->loadObject();

                        $tx_user_id = $result->tx_user_id;
                        //Changing the status of the transaction in the spc transactions table from pending to approved
                        $query = $db->getQuery(true);
                        $status="Approved";
                     
                        $query= "UPDATE #__spc_transactions SET `tx_status` = 'Approved' , `tx_balance_after` = '$newbalance' WHERE `tx_user_id` = $tx_user_id AND `tx_id` = $merchant_ref ";
                        $db->setQuery($query);
                        $result = $db->execute();                        

                        if ($result < 1) {
                            echo "Transaction was not completed";
                        }

                        else{
                            echo "Your Account has been updated with $tx_unit units";
                        }
                    }
                }
            }
        }
    }
}






