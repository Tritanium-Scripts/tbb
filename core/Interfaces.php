<?php
/**
 * API definition for a plug-in.
 */
interface PlugIn
{
    /**
     * Returns name of author responsible for this plug-in.
     *
     * @return string Author's name
     */
    public function getAuthorName(): string;

    /**
     * Returns URL to website of author responsible for this plug-in.
     *
     * @return string Author's homepage or null having none
     */
    public function getAuthorUrl(): ?string;

    /**
     * Returns name of this plug-in.
     *
     * @return string Name of plug-in
     */
    public function getName(): string;

    /**
     * Returns description of this plug-in.
     *
     * @return string Description of plug-in
     */
    public function getDescription(): string;

    /**
     * Returns version number of this plug-in.
     *
     * @return string Semantic version of plug-in
     */
    public function getVersion(): string;

    /**
     * Called on the specified hook. Hooks can be official ones from the system or custom calls e.g. from other plug-ins.
     *
     * @param string $hook Hook name
     * @param bool $official Hook being an official one
     * @return callable Callback to execute for hook or null
     */
    public function onHook(string $hook, bool $official): ?callable;
}
?>