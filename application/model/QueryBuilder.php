<?php
namespace Application\Model;

use Application\Core\FrameworkException\FrameworkException;
if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

require_once (serverPath("/core/FrameworkException.php"));

class QueryBuilder
{

    protected $config;

    protected $select;

    protected $from;

    protected $where;

    public function __construct()
    {
        if (file_exists(serverPath('/config/database.json'))) {
            $this->config = json_decode(file_get_contents(serverPath('/config/database.json')), TRUE);
        } else {
            throw new FrameworkException("The framework requires a database configuration file at the application layer", 0xdb);
        }
    }

    /**
     * <p>Returns the specific database configuration
     * settings</p>
     *
     * @author Shaun B
     * @date 18 Apr 2018 09:59:02
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * <p>Builds SELECT part of the query</p>
     *
     * @param array $items
     * @author Shaun B
     * @date 18 Apr 2018 10:32:32
     * @return self
     */
    public function select(array $items = []): self
    {
        if (empty($items)) {
            $items = [
                '*'
            ];
        }
        $_select = 'SELECT ';
        foreach ($items as $item) {
            $_select .= $item === '*' ? $item : "`{$item}`,";
        }
        if (strpos($_select, ',') > 0) {
            $_select = substr($_select, 0, - 1);
        }
        $this->select = $_select;
        return $this;
    }

    /**
     * <p>Builds the FROM part of the query</p>
     *
     * @param string $table
     * @param string $database
     * @author Shaun B
     * @date 18 Apr 2018 09:55:33
     * @return self
     * @throws FrameworkException
     */
    public function from(string $table = null, string $database = null): self
    {
        if (strlen($database) === 0) {
            $database = getConfig('db');
        }
        if (strlen($table) === 0) {
            throw new FrameworkException("Malformed SELECT statement in " . __METHOD__ . '()');
        }
        $this->from = " FROM";
        $this->from .= ! empty($database) ? " `{$database}`." : ' ';
        $this->from .= "`{$table}`";

        return $this;
    }

    /**
     * <p>Shorthand for SELECT * FROM `db`.`table`</p>
     *
     * @param array $items,
     * @param string $table
     * @param string $database
     * @author Shaun B
     * @date 18 Apr 2018 10:34:41
     * @return self
     */
    public function selectFrom(array $items = [], string $table = null, string $database = null): self
    {
        $this->select($items)->from($table, $database);
        return $this;
    }

    /**
     * <p>Builds the WHERE clause</p>
     *
     * @param array $conditions
     * @author Shaun B
     * @date 10 May 2018 16:24:09
     * @return self
     */
    public function where(array $conditions = []): self
    {
        if (empty($conditions) || ! is_array($conditions)) {
            throw new FrameworkException(__METHOD__ . '() called with incorrect parameters', 0xdb);
        }

        $_where = '';
        foreach ($conditions as $condition => $type) {
            $_where .= "`{$condition}`{$type}? ";
        }
        $this->where = substr($_where, 0, - 1);

        return $this;
    }
}
