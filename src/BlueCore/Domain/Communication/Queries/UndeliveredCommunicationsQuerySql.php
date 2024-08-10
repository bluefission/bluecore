<?php
namespace BlueFission\BlueCore\Domain\Communication\Queries;

use BlueFission\Connections\Database\MySQLLink;
use BlueFission\BlueCore\Domain\Communication\Models\CommunicationModel as Model;

use BlueFission\BlueCore\Domain\Communication\Queries\IUndeliveredCommunicationsQuery;

class UndeliveredCommunicationsQuerySql implements IUndeliveredCommunicationsQuery {
	private $_model;

	public function __construct( MySQLLink $link, Model $model )
	{
		$link->open();

		$this->_model = $model;
	}

	public function fetch() 
	{
		$model = $this->_model;
		$model->read(['status' => Communication::SENT]);
		$data = $model->result()->toArray();
		return $data;
	}
}