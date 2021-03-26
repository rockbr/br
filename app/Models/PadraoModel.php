<?php

namespace App\Models;

use CodeIgniter\Model;

class PadraoModel extends Model
{
	protected $returnType = 'array';
	protected $campoId = 'id';
	protected $campoAcao = 'acao';
	protected $useSoftDeletes = true;
	protected $useTimestamps = false;
	protected $createdField  = 'data_insert';
	protected $updatedField  = 'data_update';
	protected $deletedField  = 'data_delete';


	//Metodo GET

	/**
	 * Select Max
	 *
	 * Generates a SELECT MAX(age) as age FROM members
	 *
	 * @param string $from Nome Tabela
	 * @param string $fields Campos
	 * @param string $alias Alias
	 *
	 * @return BaseBuilder
	 */
	public function getMax($from, $fields, $alias)
	{
		$db = db_connect('default');
		$builder = $db->table($from);
		$builder->selectMax($fields, $alias);
		$results = $builder->get()->getResultArray();

		return $results;
	}

	/**
	 * Select Min
	 *
	 * Generates a SELECT MIN(age) as age FROM members
	 *
	 * @param string $from Nome Tabela
	 * @param string $fields Campos
	 * @param string $alias Alias
	 *
	 * @return BaseBuilder
	 */
	public function getMin($from, $fields, $alias)
	{
		$db = db_connect('default');
		$builder = $db->table($from);
		$builder->selectMin($fields, $alias);
		$results = $builder->get()->getResultArray();

		return $results;
	}

	/**
	 * Select Avg
	 *
	 * Generates a SELECT AVG(age) as age FROM members
	 *
	 * @param string $from Nome Tabela
	 * @param string $fields Campos
	 * @param string $alias Alias
	 *
	 * @return BaseBuilder
	 */
	public function getAvg($from, $fields, $alias)
	{
		$db = db_connect('default');
		$builder = $db->table($from);
		$builder->selectAvg($fields, $alias);
		$results = $builder->get()->getResultArray();

		return $results;
	}

	/**
	 * Select SUM
	 *
	 * Generates a SELECT SUM(age) as age FROM members
	 *
	 * @param string $from Nome Tabela
	 * @param string $fields Campos
	 * @param string $alias Alias
	 *
	 * @return BaseBuilder
	 */
	public function getSum($from, $fields, $alias)
	{
		$db = db_connect('default');
		$builder = $db->table($from);
		$builder->selectSum($fields, $alias);
		$results = $builder->get()->getResultArray();

		return $results;
	}

	/**
	 * Select Count
	 *
	 * Generates a SELECT Count('field') FROM members
	 *
	 * @param string $from Nome Tabela
	 * @param string $fields Campos
	 * @param string $alias Alias
	 *
	 * @return BaseBuilder
	 */
	public function getCount($from, $fields, $alias, $whereKey, $whereValue)
	{
		$db = db_connect('default');
		$builder = $db->table($from);
		$builder->where($whereKey, $whereValue);
		$builder->selectCount($fields, $alias);
		$results = $builder->get()->getResultArray();

		return $results;
	}

	/**
	 * Select NumRowst
	 *
	 * Generates a SELECT Count(*) FROM members
	 *
	 * @param string $from Nome Tabela
	 *
	 * @return BaseBuilder
	 */
	public function getNumRowst($from)
	{
		$db = db_connect('default');
		$builder = $db->table($from);
		return $builder->countAllResults();
	}

	/**
	 * Gerar Dados
	 *
	 * Generates a SELECT 
	 *
	 * @param Array $params Parametros
	 *
	 * @return Array
	 */
	function getQuery($params = array(), $count = false)
	{
		#region Parametros

		#region Distinct

		$distinct = (array_key_exists("distinct", $params) ? $params['distinct'] : false);

		#endregion

		#region Fields

		if ($count == true)
			$fields =  "count(*)";
		else
			$fields = (array_key_exists("fields", $params) ? implode(',', $params['fields']) : "*");

		#endregion

		#region From

		$from =  (array_key_exists("from", $params) ? $params['from'] : null);

		#endregion

		#region Where

		$where = (array_key_exists("where", $params) ? $params['where'] : null);
		$orWhere = (array_key_exists("or_where", $params) ? $params['or_where'] : null);

		#region Where In

		$whereIn = (array_key_exists("where_in", $params) ? $params['where_in'] : null);
		$orWhereIn = (array_key_exists("or_where_in", $params) ? $params['or_where_in'] : null);

		#endregion

		#region Where Not In

		$whereNotIn = (array_key_exists("where_not_in", $params) ? $params['where_not_in'] : null);
		$orWhereNotIn = (array_key_exists("or_where_not_in", $params) ? $params['or_where_not_in'] : null);

		#endregion

		#endregion

		#region Join

		//array('tabela'=> array('tabela.id = tabela2.id2','left'))
		//Option: left | right | outer | inner | left outer | right outer			
		$join = (array_key_exists("join", $params) ? $params['join'] : null);

		#endregion

		#region Group BY

		$groupBy = (array_key_exists("group_by", $params) ? $params['group_by'] : null);

		#endregion

		#region Having

		$having = (array_key_exists("having", $params) ? $params['having'] : null);
		$orHaving = (array_key_exists("or_having", $params) ? $params['or_having'] : null);

		#endregion

		#region Like  

		$like = (array_key_exists("like", $params) ? $params['like'] : null);
		$likeMatch = (array_key_exists("like_match", $params) ? $params['like_match'] : "");
		//Side: before -> %match | after -> match% | none -> match | both -> %match%
		$likeSide = (array_key_exists("like_side", $params) ? $params['like_side'] : "both");

		#region Or Like

		$orLike = (array_key_exists("or_like", $params) ? $params['or_like'] : null);
		$orLikeMatch = (array_key_exists("or_like_match", $params) ? $params['or_like_match'] : "");
		//Side: before -> %match | after -> match% | none -> match | both -> %match%
		$orLikeSide = (array_key_exists("or_like_side", $params) ? $params['or_like_side'] : "both");

		#endregion

		#region Not Like

		$notlike = (array_key_exists("not_like", $params) ? $params['not_like'] : null);
		$notlikeMatch = (array_key_exists("not_like_match", $params) ? $params['not_like_match'] : "");
		//Side: before -> %match | after -> match% | none -> match | both -> %match%
		$notlikeSide = (array_key_exists("not_like_side", $params) ? $params['not_like_side'] : "both");

		#endregion

		#region Not Or Like

		$notOrLike = (array_key_exists("not_or_like", $params) ? $params['or_like'] : null);
		$notOrLikeMatch = (array_key_exists("not_or_like_match", $params) ? $params['not_or_like_match'] : "");
		//Side: before -> %match | after -> match% | none -> match | both -> %match%
		$notOrLikeSide = (array_key_exists("not_or_like_side", $params) ? $params['not_or_like_side'] : "both");

		#endregion

		#endregion

		#region Order By
		if ($count != true) {
			$orderBy = array_key_exists("order_by", $params) ? $params['order_by'] : null;
			//Direction: ASC | DESC | RANDOM
			$orderByDirection = array_key_exists("order_by_direction", $params) ? $params['order_by_direction'] : null;
		}

		#endregion

		#region Paginacao

		if ($count != true) {
			$limit = array_key_exists("limit", $params) ? $params['limit'] : null;
			$offset = array_key_exists("offset", $params) ? $params['offset'] : null;
		}

		#endregion

		#endregion

		#region Comandos

		$db = db_connect('default');
		$builder = $db->table($from);

		#region SELECT

		$builder->select($fields);

		#endregion

		#region DISTINCT

		if ($distinct == true) {
			$builder->distinct();
		}

		#endregion

		#region WHERE

		if ($where != null) {
			foreach ($where as $key => $value) {
				if ($value != null && $value != '')
					$builder->where($key, $value);
			}
			if ($orWhere != null) {
				foreach ($orWhere as $key => $value) {
					if ($value != null && $value != '')
						$builder->orWhere($key, $value);
				}
			}
		}

		//WHERE IN
		if ($whereIn != null) {
			foreach ($whereIn as $key => $value) {
				if ($value != null && $value != '')
					$builder->whereIn($key, $value);
			}
			if ($orWhereIn != null) {
				foreach ($orWhereIn as $key => $value) {
					if ($value != null && $value != '')
						$builder->orWhereIn($key, $value);
				}
			}
		}

		//WHERE NOT IN
		if ($whereNotIn != null) {
			foreach ($whereNotIn as $key => $value) {
				if ($value != null && $value != '')
					$builder->whereNotIn($key, $value);
			}
			if ($orWhereNotIn != null) {
				foreach ($orWhereNotIn as $key => $value) {
					if ($value != null && $value != '')
						$builder->orWhereNotIn($key, $value);
				}
			}
		}

		#endregion

		#region JOIN

		if ($join != null) {

			//array('tabela'=> array('tabela.id = tabela2.id2','left')),//Option: left | right | outer | inner | left outer | right outer			
			foreach ($join as $key => $value) {
				if ($value[0] != null &&  $value[1] != null) {
					$builder->join($key, $value[0], $value[1]);
					//print_r($key . ' ON ' . $value[0]. ' OPTION ' .$value[1]);
					//die;
				}
			}
		}

		#endregion

		#region LIKE

		if ($like != null && $likeMatch != null && $likeSide != null) {
			$indiceLike = 0;
			$indiceOrLike = 0;

			foreach ($like as $value) {
				if ($value != null && $likeMatch[$indiceLike] != null && $likeSide[$indiceLike] != null) {
					$builder->like($value, $likeMatch[$indiceLike], $likeSide[$indiceLike]);
				}
				$indiceLike++;
			}

			if ($orLike != null && $orLikeMatch != null && $orLikeSide != null) {
				foreach ($orLike as $value) {
					if ($value != null && $orLikeMatch[$indiceOrLike] != null && $orLikeSide[$indiceOrLike] != null) {
						$builder->orLike($value, $orLikeMatch[$indiceOrLike], $orLikeSide[$indiceOrLike]);
					}
					$indiceOrLike++;
				}
			}
		}

		//NOT LIKE

		if ($notlike != null && $notlikeMatch != null && $notlikeSide != null) {
			$indiceNotlike = 0;
			$indiceNotOrLike = 0;

			foreach ($notlike as $value) {
				if ($value != null && $notlikeMatch[$indiceNotlike] != null && $notlikeSide[$indiceNotlike] != null) {
					$builder->notLike($value, $notlikeMatch[$indiceNotlike], $notlikeSide[$indiceNotlike]);
				}
				$indiceNotlike++;
			}

			if ($notOrLike != null && $notOrLikeMatch != null && $notOrLikeSide != null) {
				foreach ($notOrLike as $value) {
					if ($value != null && $notOrLikeMatch[$indiceNotOrLike] != null && $notOrLikeSide[$indiceNotOrLike] != null) {
						$builder->orNotLike($value, $notOrLikeMatch[$indiceNotOrLike], $notOrLikeSide[$indiceNotOrLike]);
					}
					$indiceNotOrLike++;
				}
			}
		}

		#endregion	

		#region GROUP BY

		//having so funciona se tiver group
		if ($groupBy  != null) {
			foreach ($groupBy as $value) {
				$builder->groupBy($value);
			}
		}

		#endregion

		#region HAVING

		//having so funciona se tiver group
		if ($groupBy  != null && $having != null) {
			$builder->having($having);

			if ($orHaving != null) {
				$builder->orHaving($orHaving);
			}
		}

		#endregion

		#region ORDER BY		
		if ($count != true && $orderBy != null && $orderByDirection != null) {
			$builder->orderBy($orderBy, $orderByDirection);
		}

		#endregion

		#region LIMIT

		if ($count != true && $limit != null) {
			$builder->limit($limit);

			if ($offset != null) {
				$builder->limit($limit, $offset);
			}
		}

		#endregion

		#endregion

		//Where para Soft Delete
		if ($this->deletedField != null) {
			$builder->where(($join != null ? ($from . '.' . $this->deletedField) : $this->deletedField) . ' IS NULL');
		}

		//Get records
		$results = $builder->get()->getResultArray();

		return $results;
	}

	/**
	 * Gerar Dados
	 *
	 * Generates a SELECT 
	 *
	 * @param Array $params Parametros
	 *
	 * @return Array
	 */
	function getQueryCustom($sql)
	{

		$db = db_connect('default');
		$results = $db->query($sql)->getResultArray();
		return $results;
	}

		/**
	 * Gerar Dados
	 *
	 * Generates a SELECT 
	 *
	 * @param Array $params Parametros
	 *
	 * @return Array
	 */
	function getQueryCustomTrio($sql)
	{

		$db = db_connect('trio');
		$results = $db->query($sql)->getResultArray();
		return $results;
	}

	/**
	 * Insert
	 *
	 * Generates a INSERT INTO tabela(id) VALUES (?);
	 *
	 * @param Array $from Tabela
	 * @param Array $data Dados
	 *
	 * @return Array
	 */
	public function setInsert($from, $data)
	{
		try {

			if ($from != null && $data != null) {
				$db = db_connect('default');
				$builder = $db->table($from);

				if ($this->createdField != null) {
					$builder->set($this->createdField, date('Y-m-d H:i:s'));
				}

				$builder->insert($data);
				return $db->insertID();
			}
		} catch (\Exception $e) {
			//echo $e;
			return 0;
		}
	}


	/**
	 * Insert Or Update
	 *
	 * Generates a INSERT INTO tabela(id) VALUES (?);
	 *
	 * @param Array $from Tabela
	 * @param Array $data Dados
	 *
	 * @return Array
	 */
	public function setInsertOrUpdate($from, $data, $where)
	{
		try {

			if ($from != null && $data != null && $where != null) {
				$db = db_connect('default');
				$builder = $db->table($from);
				$builder->select(array('id'));
				if ($where != null) {
					foreach ($where as $key => $value) {
						$builder->where($key, $value);
					}
				}
				$builder->limit(1);
				//Get records
				$results = $builder->get()->getResultArray();

				//Update
				if (count($results) > 0) {
					$dbUpdate = db_connect('default');
					$builderUpdate = $dbUpdate->table($from);
					//Data Update
					if ($this->updatedField != null) {
						$builderUpdate->set($this->updatedField, date('Y-m-d H:i:s'));
					}
					//SET
					foreach ($data as $key => $value) {
						$builderUpdate->set($key, $value);
					}
					//WHERE
					if ($where != null) {
						foreach ($where as $key => $value) {
							$builderUpdate->where($key, $value);
						}
					}

					$builderUpdate->update();
					return $results[0]['id'];
				} else {
					$dbInsert = db_connect('default');
					$builderInsert = $dbInsert->table($from);
					if ($this->createdField != null) {
						$builderInsert->set($this->createdField, date('Y-m-d H:i:s'));
					}

					$builderInsert->insert($data);
					return $dbInsert->insertID();
				}
			}
		} catch (\Exception $e) {
			return 0;
			//echo $e;
		}
	}

	/**
	 * Insert
	 *
	 * Generates a INSERT INTO tabela(id) VALUES (?);
	 *
	 * @param Array $from Tabela
	 * @param Array $data Dados
	 *
	 * @return Array
	 */
	public function setInsertUpdateTransactions($from, $data, $id, $fk, $relationships)
	{
		if ($from != null && $data != null) {
			$db = db_connect('default');
			$db->transStart();

			#region TABELA PRINCIPAL

			$builder = $db->table($from);

			if ($id != null) {
				//Data Update
				if ($this->updatedField != null) {
					$builder->set($this->updatedField, date('Y-m-d H:i:s'));
				}
				//SET
				foreach ($data as $key => $value) {
					$builder->set($key, $value);
				}
				//WHERE
				$builder->where($this->campoId, $id);
				$builder->update();
			} else {
				//Data Insert
				if ($this->createdField != null) {
					$builder->set($this->createdField, date('Y-m-d H:i:s'));
				}
				$builder->insert($data);
				$id = $db->insertID();
			}

			#endregion

			if ($relationships != null) {
				foreach ($relationships as $key => $value) {
					foreach ($value as $value2) {

						if ($value2[$this->campoAcao] == 'I') {
							$builder = $db->table($key);

							//Remove o ID e Ação pois não pode fazer insert desse campo
							unset($value2[$this->campoId]);
							unset($value2[$this->campoAcao]);

							//Merge com a FK
							$merge = array_merge($value2, array($fk => $id)); //adiciona o id

							//Data Insert
							if ($this->createdField != null) {
								$builder->set($this->createdField, date('Y-m-d H:i:s'));
							}
							$builder->insert($merge);
						} else if ($value2[$this->campoAcao] == 'U') {
							$builder = $db->table($key);
							$idRelationship = $value2[$this->campoId];

							//Remove o ID e Ação pois não pode fazer update desse campo
							unset($value2[$this->campoId]);
							unset($value2[$this->campoAcao]);

							$merge = array_merge($value2, array($fk => $id)); //adiciona o id

							//SET
							foreach ($merge as $keyMerge => $valueMerge) {
								$builder->set($keyMerge, $valueMerge);
							}

							//Data Update
							if ($this->updatedField != null) {
								$builder->set($this->updatedField, date('Y-m-d H:i:s'));
							}

							//WHERE
							$builder->where($this->campoId, $idRelationship);

							$builder->update();
						} else if ($value2[$this->campoAcao] == 'D') {
							$builder = $db->table($key);
							$idRelationship = $value2[$this->campoId];

							//Remove o ID e Ação pois não pode fazer update desse campo
							unset($value2[$this->campoId]);
							unset($value2[$this->campoAcao]);

							if ($this->deletedField != null) {
								$builder->set($this->deletedField, date('Y-m-d H:i:s'));
							}

							//WHERE
							$builder->where($this->campoId, $idRelationship);

							$builder->update();
						}
					}
				}
			}

			$db->transComplete();
		}
	}

	/**
	 * Update
	 *
	 * Generates a UPDATE tabela SET id=? WHERE <condition>;
	 *
	 * @param Array $from Tabela
	 * @param Array $set Dados
	 * @param Array $where Condições
	 * @param Array $orWhere Condições OU
	 * 
	 * @return Array
	 */
	public function setUpdate($from, $set, $params)
	{
		if ($from != null && $set != null) {
			$db = db_connect('default');

			#region From

			$builder = $db->table($from);

			#endregion

			#region Set

			if ($set != null) {
				foreach ($set as $key => $value) {
					$builder->set($key, $value);
				}
			}

			if ($this->updatedField != null) {
				$builder->set($this->updatedField, date('Y-m-d H:i:s'));
			}

			#endregion

			#region Where

			#region Parametros

			#region Where

			$where = (array_key_exists("where", $params) ? $params['where'] : null);
			$orWhere = (array_key_exists("or_where", $params) ? $params['or_where'] : null);

			#endregion

			#region Where In

			$whereIn = (array_key_exists("where_in", $params) ? $params['where_in'] : null);
			$orWhereIn = (array_key_exists("or_where_in", $params) ? $params['or_where_in'] : null);

			#endregion

			#region Where Not In

			$whereNotIn = (array_key_exists("where_not_in", $params) ? $params['where_not_in'] : null);
			$orWhereNotIn = (array_key_exists("or_where_not_in", $params) ? $params['or_where_not_in'] : null);

			#endregion

			#endregion

			#region Comandos

			//Where
			if ($where != null) {
				foreach ($where as $key => $value) {
					$builder->where($key, $value);
				}
				if ($orWhere != null) {
					foreach ($orWhere as $key => $value) {
						$builder->orWhere($key, $value);
					}
				}
			}

			//WHERE IN
			if ($whereIn != null) {
				foreach ($whereIn as $key => $value) {
					$builder->whereIn($key, $value);
				}
				if ($orWhereIn != null) {
					foreach ($orWhereIn as $key => $value) {
						$builder->orWhereIn($key, $value);
					}
				}
			}

			//WHERE NOT IN
			if ($whereNotIn != null) {
				foreach ($whereNotIn as $key => $value) {
					$builder->whereNotIn($key, $value);
				}
				if ($orWhereNotIn != null) {
					foreach ($orWhereNotIn as $key => $value) {
						$builder->orWhereNotIn($key, $value);
					}
				}
			}

			#endregion

			#endregion

			$builder->update();
		}
	}

	/**
	 * Delete
	 *
	 * Generates a DELETE FROM tabela WHERE <condition>;
	 *
	 * @param Array $from Tabela	 
	 * @param Array $where Condições	 
	 * 
	 * @return Array
	 */
	public function setDeleteSoft($from, $params)
	{
		if ($from != null && $params != null && $this->deletedField != null) {
			$db = db_connect('default');

			$builder = $db->table($from);
			$builder->set($this->deletedField, date('Y-m-d H:i:s'));

			#region Where

			#region Parametros

			#region Where

			$where = (array_key_exists("where", $params) ? $params['where'] : null);
			$orWhere = (array_key_exists("or_where", $params) ? $params['or_where'] : null);

			#endregion

			#region Where In

			$whereIn = (array_key_exists("where_in", $params) ? $params['where_in'] : null);
			$orWhereIn = (array_key_exists("or_where_in", $params) ? $params['or_where_in'] : null);

			#endregion

			#region Where Not In

			$whereNotIn = (array_key_exists("where_not_in", $params) ? $params['where_not_in'] : null);
			$orWhereNotIn = (array_key_exists("or_where_not_in", $params) ? $params['or_where_not_in'] : null);

			#endregion

			#endregion

			#region Comandos

			//Where
			if ($where != null) {
				foreach ($where as $key => $value) {
					$builder->where($key, $value);
				}
				if ($orWhere != null) {
					foreach ($orWhere as $key => $value) {
						$builder->orWhere($key, $value);
					}
				}
			}

			//WHERE IN
			if ($whereIn != null) {
				foreach ($whereIn as $key => $value) {
					$builder->whereIn($key, $value);
				}
				if ($orWhereIn != null) {
					foreach ($orWhereIn as $key => $value) {
						$builder->orWhereIn($key, $value);
					}
				}
			}

			//WHERE NOT IN
			if ($whereNotIn != null) {
				foreach ($whereNotIn as $key => $value) {
					$builder->whereNotIn($key, $value);
				}
				if ($orWhereNotIn != null) {
					foreach ($orWhereNotIn as $key => $value) {
						$builder->orWhereNotIn($key, $value);
					}
				}
			}

			#endregion

			#endregion

			$builder->update();
		}
	}

	/**
	 * Delete
	 *
	 * Generates a DELETE FROM tabela WHERE <condition>;
	 *
	 * @param Array $from Tabela	 
	 * @param Array $where Condições	 
	 * 
	 * @return Array
	 */
	public function setDelete($from, $params)
	{
		if ($from != null && $params != null) {
			$db = db_connect('default');
			$builder = $db->table($from);

			#region Where

			#region Parametros

			#region Where

			$where = (array_key_exists("where", $params) ? $params['where'] : null);
			$orWhere = (array_key_exists("or_where", $params) ? $params['or_where'] : null);

			#endregion

			#region Where In

			$whereIn = (array_key_exists("where_in", $params) ? $params['where_in'] : null);
			$orWhereIn = (array_key_exists("or_where_in", $params) ? $params['or_where_in'] : null);

			#endregion

			#region Where Not In

			$whereNotIn = (array_key_exists("where_not_in", $params) ? $params['where_not_in'] : null);
			$orWhereNotIn = (array_key_exists("or_where_not_in", $params) ? $params['or_where_not_in'] : null);

			#endregion

			#endregion

			#region Comandos

			//Where
			if ($where != null) {
				foreach ($where as $key => $value) {
					$builder->where($key, $value);
				}
				if ($orWhere != null) {
					foreach ($orWhere as $key => $value) {
						$builder->orWhere($key, $value);
					}
				}
			}

			//WHERE IN
			if ($whereIn != null) {
				foreach ($whereIn as $key => $value) {
					$builder->whereIn($key, $value);
				}
				if ($orWhereIn != null) {
					foreach ($orWhereIn as $key => $value) {
						$builder->orWhereIn($key, $value);
					}
				}
			}

			//WHERE NOT IN
			if ($whereNotIn != null) {
				foreach ($whereNotIn as $key => $value) {
					$builder->whereNotIn($key, $value);
				}
				if ($orWhereNotIn != null) {
					foreach ($orWhereNotIn as $key => $value) {
						$builder->orWhereNotIn($key, $value);
					}
				}
			}

			#endregion

			#endregion

			$builder->delete();
		}
	}

	/**
	 * Truncate
	 *
	 * Generates a TRUNCATE FROM tabela;
	 *
	 * @param Array $from Tabela	 	  
	 * 
	 * @return Array
	 */
	public function setTruncate($from)
	{
		if ($from != null) {
			$db = db_connect('default');
			$builder = $db->table($from);
			$builder->truncate();
		}
	}
}
