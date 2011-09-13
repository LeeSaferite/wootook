<?php

class Legacies_Core_Block_Html_Navigation
    extends Legacies_Core_Block_Template
{
    public function addLink($name, $label, $title, $uri, Array $params = array(), Array $classes = array(), $template = null)
    {
        $explodedPath = explode('/', $name);
        $baseName = array_pop($explodedPath);
        $parent = $this->_getNode($explodedPath);

        $child = $this->getLayout()
            ->createBlock('core/html.navigation.link', $this->getNameInLayout() . '.' . $baseName, array(
                'url' => array(
                    'uri'    => $uri,
                    'params' => $params
                    ),
                'label'   => $label,
                'title'   => $title,
                'classes' => $classes
                ));
        $parent->setPartial($baseName, $child);

        if ($template !== null) {
            $child->setTemplate($template);
        }

        return $this;
    }

    public function setNodeTitle($path, $title)
    {
        return $this->getNode($path)->setTitle($title);
    }

    public function setNodeTemplate($path, $template)
    {
        return $this->getNode($path)->setTemplate($template);
    }

    public function getNode($path)
    {
        return $this->_getNode(explode('/', $path));
    }

    protected function _getNode($explodedPath)
    {
        $name = array_shift($explodedPath);

        if ($this->hasPartial($name)) {
            $child = $this->getPartial($name)->_getNode($explodedPath);
        } else {
            $child = $this->getLayout()
                ->createBlock('core/html.navigation.node', $this->getNameInLayout() . '.' . $name);
        }

        if ($child instanceof Legacies_Core_Block_Html_Navigation_Link) {
            throw new RuntimeException('Node is a link. Could not append a child node to a link node.');
        }

        if (count($explodedPath) == 0) {
            return $child;
        }
        return $child->_getNode($explodedPath);
    }
}