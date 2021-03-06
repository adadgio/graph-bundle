AdadgioGraphBundle
====

Awesome helpers to use a Neo4j database inside a Symfony2 (or 3) project. This does not replace doctrine entities but is aimed at syncing some entities
with a graph database or making queries, retrieving nodes and relationships. It is *not a full ORM replacement* !

## Install

Install with composer.

`composer require adadgio/graph-bundle`

Make the following change to your `AppKernel.php` file to the registered bundles array.

```
new Adadgio\GraphBundle\AdadgioGraphBundle(),
```

# Usage

## Cypher and query builder

```php
use Adadgio\GraphBundle\ORM\Cypher;

$cypher = (new Cypher())
    ->match('a', 'Document')
    ->match('b', 'Page')
    ->andReturn('a.id, a.name AS `a.test`, b.id, b.name')->withLabels()->withId();

// is the same as
$cypher = (new Cypher())
    ->match('a', 'Document')
    ->match('b', 'Page')
    ->andReturn('a.id, a.name AS `a.test`, b.id, b.name, labels(a), labels(b), id(a), id(b)');

$manager = $this
    ->get('adadgio_graph.neo4j_manager')
    ->cypher($cypher);

// all results
print_r(manager->getResult());

// result set for "a" nodes
print_r(manager->getResult('a'));

// result set for "b" nodes
print_r(manager->getResult('b'));

// trying queries with relationships constraints (and passing string to manager instead of object)
$queryString = (new Cypher())
    ->match('a', 'Countries', array('id' => 5))
    ->newPattern()
    ->match('b', ':City:Town')
        ->relatedWith('a')
        ->by('r', 'IS_IN', array('max' => 3), '->')
    ->newPattern()
    ->match('a')
    ->getQuery();

$manager = $this
    ->get('adadgio_graph.neo4j_manager')
    ->cypher($queryString);

```

## Queries and transactions

When doing transactions, no result set is available from the manager

```php
$cypherA = (new Cypher())
    ->match('a', 'Document', array('id' => 389))
    ->set('a', array('name' => 'The good, the bad and the ugly'));

$cypherB = (new Cypher())
    ->match('a', 'Document', array('id' => 390))
    ->set('a', array('name' => 'The good, the bad and the ugly'));

$manager = $this
    ->get('adadgio_graph.neo4j_manager')
    ->transaction(array($cypherA, $cypherB));
```

## Creating, setting, merging and deleting

# Components

## User components

**Query builder**

Help build Neo4j cypher queries in a convinient and secure way, just like an ORM would.

```$query = new (Cypher())->match(...)```

**Manager**

Performs cypher queries, native queries or transactions (multiple queries) as in

```
$this->get('adadgio_graph.neo4j_manager')->cypher(\Cypher or native query)
```

## Backend components

**GraphAnnotationReader**

Reads class and property annotations from an entity to merge those properties into Neo4j graph nodes.

```
use Adadgio\GraphBundle\Annotation\GraphAnnotation;

/**
 * @ORM\Column(type="string", length=64)
 * @Graph(name="surname", type="string")
 */
private $surname;
```

**Graph**

The annotation defining class, just getters and setters.
