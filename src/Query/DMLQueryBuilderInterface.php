<?php

declare(strict_types=1);

namespace Yiisoft\Db\Query;

use Generator;
use JsonException;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;

interface DMLQueryBuilderInterface
{
    /**
     * Generates a batch INSERT SQL statement.
     *
     * For example,
     *
     * ```php
     * $sql = $queryBuilder->batchInsert('user', ['name', 'age'], [
     *     ['Tom', 30],
     *     ['Jane', 20],
     *     ['Linda', 25],
     * ]);
     * ```
     *
     * Note that the values in each row must match the corresponding column names.
     *
     * The method will properly escape the column names, and quote the values to be inserted.
     *
     * @param string $table the table that new rows will be inserted into.
     * @param array $columns the column names.
     * @param Generator|iterable $rows the rows to be batched inserted into the table.
     * @param array $params the binding parameters. This parameter exists.
     *
     * @throws Exception|InvalidArgumentException
     *
     * @return string the batch INSERT SQL statement.
     */
    public function batchInsert(string $table, array $columns, iterable|Generator $rows, array &$params = []): string;

    /**
     * Creates a DELETE SQL statement.
     *
     * For example,
     *
     * ```php
     * $sql = $queryBuilder->delete('user', 'status = 0');
     * ```
     *
     * The method will properly escape the table and column names.
     *
     * @param string $table the table where the data will be deleted from.
     * @param array|string $condition the condition that will be put in the WHERE part. Please refer to
     * {@see Query::where()} on how to specify condition.
     * @param array $params the binding parameters that will be modified by this method so that they can be bound to the
     * DB command later.
     *
     * @throws Exception|InvalidArgumentException
     *
     * @return string the DELETE SQL.
     */
    public function delete(string $table, array|string $condition, array &$params): string;

    /**
     * Creates an INSERT SQL statement.
     *
     * For example,
     *
     * ```php
     * $sql = $queryBuilder->insert('user', [
     *     'name' => 'Sam',
     *     'age' => 30,
     * ], $params);
     * ```
     *
     * The method will properly escape the table and column names.
     *
     * @param string $table the table that new rows will be inserted into.
     * @param array|QueryInterface $columns the column data (name => value) to be inserted into the table or instance of
     * {@see Query} to perform INSERT INTO ... SELECT SQL statement. Passing of {@see Query}.
     * @param array $params the binding parameters that will be generated by this method. They should be bound to the
     * DB command later.
     *
     * @throws Exception|InvalidArgumentException|InvalidConfigException|NotSupportedException
     *
     * @return string the INSERT SQL.
     */
    public function insert(string $table, QueryInterface|array $columns, array &$params = []): string;

    public function insertEx(string $table, QueryInterface|array $columns, array &$params = []): string;

    /**
     * Creates a SQL statement for resetting the sequence value of a table's primary key.
     *
     * The sequence will be reset such that the primary key of the next new row inserted will have the specified value
     * or 1.
     *
     * @param string $tableName the name of the table whose primary key sequence will be reset.
     * @param array|int|string|null $value the value for the primary key of the next new row inserted. If this is not
     * set, the next new row's primary key will have a value 1.
     *
     * @throws Exception|NotSupportedException if this is not supported by the underlying DBMS.
     *
     * @return string the SQL statement for resetting sequence.
     */
    public function resetSequence(string $tableName, array|int|string|null $value = null): string;

    /**
     * Builds a SQL statement for truncating a DB table.
     *
     * @param string $table the table to be truncated. The name will be properly quoted by the method.
     *
     * @return string the SQL statement for truncating a DB table.
     */
    public function truncateTable(string $table): string;

    /**
     * Creates an UPDATE SQL statement.
     *
     * For example,
     *
     * ```php
     * $params = [];
     * $sql = $queryBuilder->update('user', ['status' => 1], 'age > 30', $params);
     * ```
     *
     * The method will properly escape the table and column names.
     *
     * @param string $table the table to be updated.
     * @param array $columns the column data (name => value) to be updated.
     * @param array|string $condition the condition that will be put in the WHERE part. Please refer to
     * {@see Query::where()} on how to specify condition.
     * @param array $params the binding parameters that will be modified by this method so that they can be bound to the
     * DB command later.
     *
     * @throws Exception|InvalidArgumentException
     *
     * @return string the UPDATE SQL.
     */
    public function update(string $table, array $columns, array|string $condition, array &$params = []): string;

    /**
     * Creates an SQL statement to insert rows into a database table if they do not already exist (matching unique
     * constraints), or update them if they do.
     *
     * For example,
     *
     * ```php
     * $sql = $queryBuilder->upsert('pages', [
     *     'name' => 'Front page',
     *     'url' => 'http://example.com/', // url is unique
     *     'visits' => 0,
     * ], [
     *     'visits' => new Expression('visits + 1'),
     * ], $params);
     * ```
     *
     * The method will properly escape the table and column names.
     *
     * @param string $table the table that new rows will be inserted into/updated in.
     * @param array|QueryInterface $insertColumns the column data (name => value) to be inserted into the table or
     * instance of {@see Query} to perform `INSERT INTO ... SELECT` SQL statement.
     * @param array|bool $updateColumns the column data (name => value) to be updated if they already exist. If `true`
     * is passed, the column data will be updated to match the insert column data. If `false` is passed, no update will
     * be performed if the column data already exists.
     * @param array $params the binding parameters that will be generated by this method. They should be bound to the DB
     * command later.
     *
     * @throws Exception|InvalidConfigException|JsonException|NotSupportedException if this is not supported by the
     * underlying DBMS.
     *
     * @return string the resulting SQL.
     */
    public function upsert(
        string $table,
        QueryInterface|array $insertColumns,
        bool|array $updateColumns,
        array &$params
    ): string;
}
