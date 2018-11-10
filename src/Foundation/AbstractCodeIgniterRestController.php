<?php

namespace Chaos\Foundation;

/**
 * Class AbstractCodeIgniterRestController
 * @author ntd1712
 *
 * @method void set_response($data = null, $http_code = null)
 * @method mixed get($key = null, $xss_clean = null)
 */
abstract class AbstractCodeIgniterRestController extends AbstractCodeIgniterController
{
    /**
     * The default `index` action, you can override this in the derived class.
     * GET /lookup?filter=&sort=&start=&length=
     *
     * @return  void
     */
    public function index_get()
    {
        $data = $this->getService()->readAll($this->getFilterParams(), $this->getPagerParams());
        $this->set_response($data);
    }

    /**
     * The default `create` action, you can override this in the derived class.
     * GET /lookup/create
     *
     * @return  void
     * @throws  \BadMethodCallException
     */
    public function create_get()
    {
        throw new \BadMethodCallException('Unknown method ' . __METHOD__);
    }

    /**
     * The default `store` action, you can override this in the derived class.
     * POST /lookup
     *
     * @return  void
     */
    public function store_post()
    {
        $data = $this->getService()->create($this->getRequest());
        $this->set_response($data);
    }

    /**
     * The default `show` action, you can override this in the derived class.
     * GET /lookup/{lookup}
     *
     * @return  void
     */
    public function show_get()
    {
        $data = $this->getService()->read($this->get('id'));
        $this->set_response($data);
    }

    /**
     * The default `edit` action, you can override this in the derived class.
     * GET /lookup/{lookup}/edit
     *
     * @return  void
     * @throws  \BadMethodCallException
     */
    public function edit_get()
    {
        throw new \BadMethodCallException('Unknown method ' . __METHOD__);
    }

    /**
     * The default `update` action, you can override this in the derived class.
     * PUT /lookup/{lookup}
     *
     * @return  void
     */
    public function update_put()
    {
        $data = $this->getService()->update($this->getRequest(), $this->get('id'));
        $this->set_response($data);
    }

    /**
     * The default `destroy` action, you can override this in the derived class.
     * DELETE /lookup/{lookup}
     *
     * @return  void
     */
    public function destroy_delete()
    {
        $data = $this->getService()->delete($this->get('id'));
        $this->set_response($data);
    }
}
