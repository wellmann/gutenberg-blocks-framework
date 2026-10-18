import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import metadata from './block.json';

registerBlockType(metadata.name, {
  edit: ({ attributes, setAttributes }) => (
    <div {...useBlockProps()}>
      <InspectorControls>
        <PanelBody title="Settings">
          <TextControl
            label="Message"
            value={attributes.message}
            onChange={(message) => setAttributes({ message })}
          />
        </PanelBody>
      </InspectorControls>
      <ServerSideRender block={metadata.name} attributes={attributes} />
    </div>
  ),
  save: () => null
});
