<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Currency Model
 *
 * @method \App\Model\Entity\Currency newEmptyEntity()
 * @method \App\Model\Entity\Currency newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Currency[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Currency get($primaryKey, $options = [])
 * @method \App\Model\Entity\Currency findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Currency patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Currency[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Currency|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Currency saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Currency[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Currency[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Currency[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Currency[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CurrencyTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('currency');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    /**
     * Callback method triggered before a find operation is executed.
     *
     * @param \Cake\Event\Event $event The event object.
     * @param \Cake\ORM\Query $query The query object representing the find operation.
     * @param array $options Additional options passed to the find operation.
     * @return void
     */
    public function beforeFind($event, $query, $options)
    {
        // Modify the query to replace current_rate and previous_rate with their formatted values without trailing zeros
        $query->formatResults(function ($results) {
            return $results->map(function ($row) {
                $row['current_rate'] = (float)rtrim(rtrim($row['current_rate'], '0'), '.');
                $row['previous_rate'] = (float)rtrim(rtrim($row['previous_rate'], '0'), '.');
                return $row;
            });
        });
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('iso_code')
            ->maxLength('iso_code', 3)
            ->requirePresence('iso_code', 'create')
            ->notEmptyString('iso_code')
            ->add('iso_code', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->decimal('current_rate')
            ->requirePresence('current_rate', 'create')
            ->notEmptyString('current_rate');

        $validator
            ->decimal('previous_rate')
            ->requirePresence('previous_rate', 'create')
            ->notEmptyString('previous_rate');

        $validator
            ->scalar('base_currency')
            ->maxLength('base_currency', 3)
            ->requirePresence('base_currency', 'create')
            ->notEmptyString('base_currency');

        $validator
            ->dateTime('created_at')
            ->allowEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->allowEmptyDateTime('updated_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['iso_code']), ['errorField' => 'iso_code']);

        return $rules;
    }
}
