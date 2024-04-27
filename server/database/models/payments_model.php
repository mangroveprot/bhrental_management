<?php
require_once dirname(__DIR__, 2) . '/database/db_connections.php';
class PaymentsModel
{
    private $connect;

    public function __construct()
    {
        $this->connect = connections();
    }

    public function getAllPayments()
    {
        $transactionData = [];

        try {
            $stmt = $this->connect->query('SELECT * FROM payments');
            $stmt->execute();
            $transactionData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching beds: " . $e->getMessage());
        }
        return $transactionData;
    }

    public function addNewPayments($tenantID, $amount, $dateTransaction = null)
    {
        if ($dateTransaction === null || $dateTransaction === '' || $dateTransaction === '0000-00-00') {
            $dateTransaction = date('Y-m-d');
        }
        $stmt = $this->connect->prepare('INSERT INTO payments (customer_id, amount, date_transaction) VALUES (:tenantID, :amount, :dateTransaction)');
        $stmt->bindParam(':tenantID', $tenantID);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':dateTransaction', $dateTransaction);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function deleteTransaction($transactionID)
    {
        try {
            $stmt = $this->connect->prepare('DELETE FROM payments WHERE transaction_id = :transactionID');
            $stmt->bindParam(':transactionID', $transactionID);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            throw new Exception("Error deleting transaction: " . $e->getMessage());
            return 1;
        }
    }

    public function editTransaction($transactionID, $tenantID, $amount, $dateTransaction = null)
    {
        try {
            if ($dateTransaction === null || $dateTransaction === '' || $dateTransaction === '0000-00-00') {
                $dateTransaction = date('Y-m-d');
            }
            $stmt = $this->connect->prepare('UPDATE payments SET customer_id = :tenantID, amount = :amount, date_transaction = :dateTransaction WHERE transaction_id = :transactionID');
            $stmt->bindParam(':tenantID', $tenantID);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':dateTransaction', $dateTransaction);
            $stmt->bindParam(':transactionID', $transactionID);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            throw new Exception("Error editing transaction: " . $e->getMessage());
        }
    }

    public function getTransactionByID($transactionID)
    {
        try {
            $stmt = $this->connect->prepare('SELECT * FROM payments WHERE transaction_id = :transactionID');
            $stmt->bindParam(':transactionID', $transactionID);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching transaction: " . $e->getMessage());
        }
    }


}
?>