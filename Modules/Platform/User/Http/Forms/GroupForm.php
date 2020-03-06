<?php

namespace Modules\Platform\User\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\User\Entities\User;

/**
 * Class GroupForm
 * @package Modules\Platform\User\Http\Forms
 */
class GroupForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('user::groups.form.name'),
        ]);
        $this->add('users', 'choice', [
            'choices' => User::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],

            'expanded' => false,
            'multiple' => true
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('user::groups.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
