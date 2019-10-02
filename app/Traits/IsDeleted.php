<?php

namespace App\Traits;
//use Illuminate\Database\Eloquent\Model;

trait IsDeleted
{
    public function newQuery($exclude_is_deleted = true)
    {
        // 親のメソッドを呼び出す
        // もともとはクエリビルダーを新規作成するときに呼び出されるメソッド
        $query = parent::newQuery($exclude_is_deleted);

        // すべてのクエリに deleted = 0 の条件を最初に指定
        $query = $query->where('is_deleted',0);

        return $query;

    }
}
