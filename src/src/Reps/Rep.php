<?php

namespace AkDevTodo\Backend\Reps;

use AkDevTodo\Backend\Database\DB;
use AkDevTodo\Backend\Models\Model;

class Rep
{
    private DB $db;
    protected string $table;
    protected string $wrapper;

    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * @param array $data
     * @return array
     */
    public function sanitize(array $data): array
    {
        $columns = $this->db->columns($this->table);

        return array_intersect_key($data, array_flip($columns));
    }


    /**
     * @param array $data
     * @return array
     */
    public function findBy(array $data): array
    {
        $data = $this->sanitize($data);

        $query = "SELECT * from `$this->table`";

        if (count($data)) {
            $params = [];
            foreach ($data as $key => $value) {
                $params[] = "$key = :$key";
            }
            $query .= ' WHERE ' . implode(' AND ', $params);
        }

        return $this->db->execute($query, $data, $this->wrapper);
    }


    /**
     * @param array $data
     * @return void
     */
    public function create(array $data): void
    {
        $data = $this->sanitize($data);
        $columns = array_keys($data);
        $bindParams = array_map(fn($item) => ':' . $item, $columns);

        $columns = implode(', ', $columns);
        $bindParams = implode(', ', $bindParams);

        $query = "INSERT INTO `$this->table` ( $columns ) values ( $bindParams )";

        $this->db->execute($query, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return array|void
     */
    public function update(int $id, array $data)
    {
        $data = $this->sanitize($data);

        $columns = array_keys($data);
        $changeColumns = array_map(fn($item) => "`$item` = :$item", $columns);
        $fields = implode(', ', $changeColumns);

        $query = "UPDATE `$this->table` SET $fields WHERE `id` = '$id'";

        return $this->db->execute($query, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $query = "DELETE FROM `$this->table` WHERE `id` = '$id'";

        $this->db->execute($query);
    }

}