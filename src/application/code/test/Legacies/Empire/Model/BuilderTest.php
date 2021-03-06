<?php

/**
 * Test class for Wootook_Object.
 * Generated by PHPUnit on 2011-09-01 at 11:24:52.
 */
class Test_Legacies_Empire_Model_BuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Legacies_Empire_Model_Planet
     */
    protected $_planet = null;

    /**
     * @var Legacies_Empire_Model_User
     */
    protected $_user = null;

    protected $_class = 'Legacies_Empire_Model_BuilderAbstract';

    protected $_queueItemsData = null;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_planet = new Legacies_Empire_Model_Planet(array(
            'id'       => 1,
            'id_owner' => 1
            ));

        $this->_user = new Legacies_Empire_Model_User(array(
            'id' => 1
            ));

        $this->_planet->setUser($this->_user);

        $this->_user->setCurrentPlanet($this->_planet);
        $this->_user->setHomePlanet($this->_planet);

        $this->_queueItemsData = array(
            'IDX0001' => array(
                'item_id'    => 1,
                'level'      => 1,
                'created_at' => 0,
                'updated_at' => 0
                ),
            'IDX0002' => array(
                'item_id'    => 1,
                'level'      => 2,
                'created_at' => 100,
                'updated_at' => 100
                ),
            'IDX0003' => array(
                'item_id'    => 1,
                'level'      => 3,
                'created_at' => 150,
                'updated_at' => 150
                )
            );
    }

    protected function _getMockedInstance($planet, $user, $methods = array())
    {
        $reflector = new ReflectionClass($this->_class);

        foreach ($reflector->getMethods() as $method) {
            if ($method->isAbstract()) {
                $methods[] = $method->getName();
            }
        }

        if (empty($methods)) {
            $methods = null;
        }

        return $this->getMock($this->_class, $methods, array($planet, $user));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testInit()
    {
        $stub = $this->getMockBuilder('Legacies_Empire_Model_BuilderAbstract')
            ->disableOriginalConstructor()
            ->setMethods(array('init', '_initItem', 'updateQueue', 'appendQueue', 'getResourcesNeeded', 'getBuildingTime'))
            ->getMock();

        $stub->expects($this->once())
            ->method('init')
            ->will($this->returnValue($stub));

        $stub->expects($this->never())
            ->method('_serializeQueue')
            ->will($this->returnValue($stub));

        $stub->expects($this->never())
            ->method('_unserializeQueue')
            ->will($this->returnValue($stub));

        $stub->__construct($this->_planet, $this->_user);
    }

    public function testSerializingQueue()
    {
        $items = array(
            'IDX0001' => array(
                'item_id'    => 1,
                'level'      => 1,
                'created_at' => 0,
                'updated_at' => 0
                ),
            'IDX0002' => array(
                'item_id'    => 1,
                'level'      => 2,
                'created_at' => 100,
                'updated_at' => 100
                ),
            'IDX0003' => array(
                'item_id'    => 1,
                'level'      => 3,
                'created_at' => 150,
                'updated_at' => 150
                )
            );

        $stub = $this->_getMockedInstance($this->_planet, $this->_user, array('_generateIndex'));

        $stub->expects($this->exactly(3))
            ->method('_initItem')
            ->will($this->onConsecutiveCalls(
                new Legacies_Empire_Model_Builder_Item($this->_queueItemsData['IDX0001']),
                new Legacies_Empire_Model_Builder_Item($this->_queueItemsData['IDX0002']),
                new Legacies_Empire_Model_Builder_Item($this->_queueItemsData['IDX0003'])
                ));

        $stub->expects($this->exactly(3))
            ->method('_generateIndex')
            ->will($this->onConsecutiveCalls(
                'IDX0001',
                'IDX0002',
                'IDX0003'
                ));

        $stub->enqueue($this->_queueItemsData['IDX0001']);
        $stub->enqueue($this->_queueItemsData['IDX0002']);
        $stub->enqueue($this->_queueItemsData['IDX0003']);

        $this->assertEquals(serialize($this->_queueItemsData), $stub->serialize());
    }

    public function testUnSerializingQueue()
    {
        $serializedData = serialize($this->_queueItemsData);

        $stub = $this->_getMockedInstance($this->_planet, $this->_user, array('_generateIndex'));

        $stub->expects($this->exactly(3))
            ->method('_initItem')
            ->will($this->onConsecutiveCalls(
                new Legacies_Empire_Model_Builder_Item($this->_queueItemsData['IDX0001']),
                new Legacies_Empire_Model_Builder_Item($this->_queueItemsData['IDX0002']),
                new Legacies_Empire_Model_Builder_Item($this->_queueItemsData['IDX0003'])
                ));

        $stub->expects($this->never())
            ->method('_generateIndex');

        $stub->unserialize($serializedData);

        $this->assertAttributeNotEmpty('_queue', $stub);

        $this->assertEquals(serialize($this->_queueItemsData), $stub->serialize());
    }

    public function testUnSerializingQueueWithInvalidData()
    {
        $serializedData = '';

        $stub = $this->_getMockedInstance($this->_planet, $this->_user, array('_generateIndex'));

        $stub->expects($this->never())
            ->method('_initItem');

        $stub->expects($this->never())
            ->method('_generateIndex');

        $stub->unserialize($serializedData);

        $this->assertAttributeEmpty('_queue', $stub);

        $this->assertEquals('a:0:{}', $stub->serialize());
    }

    public function testInitItemReturningNull()
    {
        $serializedData = serialize($this->_queueItemsData);

        $stub = $this->_getMockedInstance($this->_planet, $this->_user, array('_generateIndex'));

        $stub->expects($this->exactly(3))
            ->method('_initItem')
            ->will($this->returnValue(null));

        $stub->unserialize($serializedData);

        $this->assertAttributeEmpty('_queue', $stub);

        $this->assertEquals('a:0:{}', $stub->serialize());
    }

    public function testCheckAvailability()
    {
        $serializedData = serialize($this->_queueItemsData);

        $stub = $this->_getMockedInstance($this->_planet, $this->_user, array('_generateIndex'));

        $stub->expects($this->any())
            ->method('_initItem')
            ->will($this->onConsecutiveCalls(
                new Legacies_Empire_Model_Builder_Item($this->_queueItemsData['IDX0001'])
                ));

        $this->assertTrue($stub->checkAvailability(Legacies_Empire::ID_BUILDING_METAL_MINE));
        $this->assertTrue($stub->checkAvailability(Legacies_Empire::ID_BUILDING_CRISTAL_MINE));
        $this->assertTrue($stub->checkAvailability(Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER));
        $this->assertTrue($stub->checkAvailability(Legacies_Empire::ID_BUILDING_FUSION_REACTOR));
    }
}
