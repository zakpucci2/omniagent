<?php

App::uses('AppController', 'Controller');

/**
 * SupportTicketHistories Controller
 *
 * @property SupportTicketHistory $SupportTicketHistory
 * @property PaginatorComponent $Paginator
 */
class SupportTicketHistoriesController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->SupportTicketHistory->recursive = 0;
        $this->set('supportTicketHistories', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->SupportTicketHistory->exists($id)) {
            throw new NotFoundException(__('Invalid support ticket history'));
        }
        $options = array('conditions' => array('SupportTicketHistory.' . $this->SupportTicketHistory->primaryKey => $id));
        $this->set('supportTicketHistory', $this->SupportTicketHistory->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->SupportTicketHistory->create();
            if ($this->SupportTicketHistory->save($this->request->data)) {
                $this->Session->setFlash(__('The support ticket history has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The support ticket history could not be saved. Please, try again.'));
            }
        }
        $supportTickets = $this->SupportTicketHistory->SupportTicket->find('list');
        $users = $this->SupportTicketHistory->User->find('list');
        $admins = $this->SupportTicketHistory->Admin->find('list');
        $this->set(compact('supportTickets', 'users', 'admins'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->SupportTicketHistory->exists($id)) {
            throw new NotFoundException(__('Invalid support ticket history'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->SupportTicketHistory->save($this->request->data)) {
                $this->Session->setFlash(__('The support ticket history has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The support ticket history could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('SupportTicketHistory.' . $this->SupportTicketHistory->primaryKey => $id));
            $this->request->data = $this->SupportTicketHistory->find('first', $options);
        }
        $supportTickets = $this->SupportTicketHistory->SupportTicket->find('list');
        $users = $this->SupportTicketHistory->User->find('list');
        $admins = $this->SupportTicketHistory->Admin->find('list');
        $this->set(compact('supportTickets', 'users', 'admins'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->SupportTicketHistory->id = $id;
        if (!$this->SupportTicketHistory->exists()) {
            throw new NotFoundException(__('Invalid support ticket history'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->SupportTicketHistory->delete()) {
            $this->Session->setFlash(__('The support ticket history has been deleted.'));
        } else {
            $this->Session->setFlash(__('The support ticket history could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
