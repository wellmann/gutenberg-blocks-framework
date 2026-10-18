import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import metadata from './block.json';
import './style.scss';
import './editor.scss';

registerBlockType(metadata.name, {
  edit: ({ attributes, setAttributes }) => (
    <div {...useBlockProps({ className: `block-notice block-notice--${attributes.type}` })}>
      <InspectorControls>
        <PanelBody title="Settings">
          <SelectControl
            label="Type"
            value={attributes.type}
            options={[
              { label: 'Info', value: 'info' },
              { label: 'Warning', value: 'warning' }
            ]}
            onChange={(type) => setAttributes({ type })}
          />
        </PanelBody>
      </InspectorControls>
      <strong className="block-notice__label">{attributes.type}</strong>
      <div className="block-notice__content">
        <InnerBlocks />
      </div>
    </div>
  ),
  save: () => <InnerBlocks.Content />
});
