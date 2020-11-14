<?php
class Reminder_model extends CI_Model
{

	var $table = 'reminder';

	var $column_order = array(null, null, 'judul_reminder', 'isi_reminder', 'tgl_reminder', null);
	var $column_search = array('judul_reminder');
	var $order = array('id_reminder' => 'desc'); // default order

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->from($this->table);
		$this->db->join('pasien', 'reminder.id_pasien=pasien.id_pasien');
		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					//$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
				{
					//$this->db->group_end(); //close bracket
				}
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_reminder', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_reminder($limit = "")
	{
		$this->db->from($this->table);
		if ($limit != "")
			$this->db->limit($limit, 0);

		$query = $this->db->get();

		return $query->result();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_reminder', $id);
		$this->db->delete($this->table);
	}

	public function get_list_reminder()
	{
		$this->db->from($this->table);
		$this->db->order_by('tgl_reminder', 'desc');
		$query = $this->db->get();
		$result = $query->result();

		$reminders = array();
		foreach ($result as $row) {
			$reminders[$row->id_reminder] = $row->judul_reminder;
		}
		return $reminders;
	}
}
