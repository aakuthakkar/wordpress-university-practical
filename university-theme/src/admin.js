import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { useSelect, useDispatch } from '@wordpress/data';
import { useState } from '@wordpress/element';

import './admin.css';

function ProgramFields() {
	const [ expandedRows, setExpandedRows ] = useState( {} );

	const { postType, meta } = useSelect(
		( select ) => ( {
			postType: select( 'core/editor' ).getCurrentPostType(),
			meta: select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {},
		} ),
		[]
	);

	const { editPost } = useDispatch( 'core/editor' );

	if ( postType !== 'program' ) {
		return null;
	}

	const sections = Array.isArray( meta.content_sections )
		? meta.content_sections
		: [];

	const updateMeta = ( changes ) => {
		editPost( { meta: { ...meta, ...changes } } );
	};

	const updateSection = ( index, changes ) => {
		const updated = sections.map( ( section, rowIndex ) =>
			rowIndex === index ? { ...section, ...changes } : section
		);

		updateMeta( { content_sections: updated } );
	};

	const addSection = () => {
		const index = sections.length;

		updateMeta( {
			content_sections: [
				...sections,
				{ title: '', description: '', image: 0 },
			],
		} );

		setExpandedRows( { ...expandedRows, [ index ]: true } );
	};

	const removeSection = ( index ) => {
		updateMeta( {
			content_sections: sections.filter( ( _, rowIndex ) => rowIndex !== index ),
		} );

		setExpandedRows( {} );
	};

	const moveSection = ( index, direction ) => {
		const target = index + direction;

		if ( target < 0 || target >= sections.length ) {
			return;
		}

		const updated = [ ...sections ];
		[ updated[ index ], updated[ target ] ] = [
			updated[ target ],
			updated[ index ],
		];

		updateMeta( { content_sections: updated } );
		setExpandedRows( {} );
	};

	const chooseImage = ( index ) => {
		if ( ! window.wp || ! window.wp.media ) {
			return;
		}

		const frame = window.wp.media( {
			title: 'Choose section image',
			button: { text: 'Use this image' },
			multiple: false,
			library: { type: 'image' },
		} );

		frame.on( 'select', () => {
			const image = frame.state().get( 'selection' ).first().toJSON();
			updateSection( index, { image: image.id } );
		} );

		frame.open();
	};

	return (
		<PluginDocumentSettingPanel
			name="university-program-fields"
			title="Program Details"
		>
			<div className="university-program-fields">
				<label htmlFor="academic-year">Academic Year</label>
				<input
					id="academic-year"
					type="text"
					placeholder="e.g. 2025-26"
					value={ meta.academic_year || '' }
					onChange={ ( event ) =>
						updateMeta( { academic_year: event.target.value } )
					}
				/>

				<label htmlFor="duration">Duration</label>
				<input
					id="duration"
					type="text"
					placeholder="e.g. 3 Years"
					value={ meta.duration || '' }
					onChange={ ( event ) =>
						updateMeta( { duration: event.target.value } )
					}
				/>

				<h3>Content Sections</h3>

				{ sections.map( ( section, index ) => {
					const isExpanded = !! expandedRows[ index ];

					return (
						<div className="program-section-card" key={ index }>
							<button
								type="button"
								className="program-section-toggle"
								aria-expanded={ isExpanded }
								onClick={ () =>
									setExpandedRows( {
										...expandedRows,
										[ index ]: ! isExpanded,
									} )
								}
							>
								{ section.title || `Section ${ index + 1 }` }
								{ isExpanded ? ' −' : ' +' }
							</button>

							{ isExpanded && (
								<div className="program-section-content">
									<label htmlFor={ `section-title-${ index }` }>
										Title
									</label>
									<input
										id={ `section-title-${ index }` }
										type="text"
										value={ section.title || '' }
										onChange={ ( event ) =>
											updateSection( index, {
												title: event.target.value,
											} )
										}
									/>

									<label htmlFor={ `section-description-${ index }` }>
										Description
									</label>
									<textarea
										id={ `section-description-${ index }` }
										rows="4"
										value={ section.description || '' }
										onChange={ ( event ) =>
											updateSection( index, {
												description: event.target.value,
											} )
										}
									/>

									<button
										type="button"
										onClick={ () => chooseImage( index ) }
									>
										Choose image
									</button>

									{ section.image > 0 && (
										<div className="program-image-info">
											Image ID: { section.image }
											<button
												type="button"
												onClick={ () =>
													updateSection( index, { image: 0 } )
												}
											>
												Remove image
											</button>
										</div>
									) }

									<div className="program-section-actions">
										<button
											type="button"
											disabled={ index === 0 }
											onClick={ () => moveSection( index, -1 ) }
										>
											Move up
										</button>

										<button
											type="button"
											disabled={ index === sections.length - 1 }
											onClick={ () => moveSection( index, 1 ) }
										>
											Move down
										</button>

										<button
											type="button"
											onClick={ () => removeSection( index ) }
										>
											Remove section
										</button>
									</div>
								</div>
							) }
						</div>
					);
				} ) }

				<button type="button" onClick={ addSection }>
					Add section
				</button>
			</div>
		</PluginDocumentSettingPanel>
	);
}

registerPlugin( 'university-program-fields', {
	render: ProgramFields,
} );