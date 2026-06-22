<?php
class Database {

   private $pdo;
    
    public function __construct() {
        $this->pdo = new PDO("mysql:host=messenger;dbname=Social", "root", "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

	//** $sql - сам запрос, $params - параметры */
    public function query1($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    // Одна запись 
    public function fetchOne($sql, $params = []) {
        $result = $this->query1($sql, $params)->fetch();
        return $result === false ? null : $result;
    }
    
    // Все записи
    public function fetchAll($sql, $params = []){
        return $this->query1($sql, $params)->fetchAll();
    }
    
    // Вставка данных
    public function insert($table, $data) {
        // INSERT запрос
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $this->query1($sql, array_values($data));
        return $this->pdo->lastInsertId();
    }
    
    // Обновление данных
    public function update($table, $data, $where, $whereParams = []) {
        $fields = array_map(fn($field) => "{$field} = ?", array_keys($data));
        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE {$where}";
        
        $params = array_merge(array_values($data), $whereParams);
        $result = $this->query1($sql, $params)->rowCount();

		return true;
    }
    
    // Удаление данных
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query1($sql, $params)->rowCount();
    }
}
?>