<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comments`.
 */
class m161109_090940_create_comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
            'comment' => $this->text(),
            'comment_id' => $this->integer()->unsigned(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
        ]);
        $this->createIndex(
            'idx-comments-user_id',
            'comments',
            'user_id'
        );
        $this->addForeignKey(
            'fk-comments-user_id',
            'comments',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-comments-post_id',
            'comments',
            'user_id'
        );
        $this->addForeignKey(
            'fk-comments-post_id',
            'comments',
            'user_id',
            'posts',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-comments-user_id',
            'comments'
        );
        $this->dropForeignKey(
            'fk-comments-post_id',
            'comments'
        );
        $this->dropIndex(
            'idx-comments-user_id',
            'comments'
        );
        $this->dropIndex(
            'idx-comments-post_id',
            'comments'
        );
        $this->dropTable('comments');
    }
}
