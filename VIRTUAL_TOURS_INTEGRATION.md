# Virtual Tours Integration

This document describes the virtual tour functionality integrated into the real estate platform.

## Overview

The virtual tour feature allows properties to display immersive 3D tours and enables users to schedule live virtual tours with agents. This enhances the property viewing experience and provides modern, convenient ways for potential buyers/renters to explore properties remotely.

## Features

### 1. Self-Guided Virtual Tours

Properties can have embedded 3D virtual tours from popular providers such as:
- **Matterport** - Industry-leading 3D virtual tours
- **Kuula** - 360° virtual tour platform
- **3D Vista** - Virtual tour software
- **Seekbeak** - Interactive virtual tours
- **Custom embeds** - Support for any other provider via custom embed code

#### Usage

To add a virtual tour to a property:

1. Navigate to the property in the admin panel (Filament)
2. Enter the virtual tour URL in the "Virtual Tour URL" field
3. Select the provider from the dropdown (optional - helps with auto-generation)
4. Alternatively, paste custom embed code in the "Virtual Tour Embed Code" field
5. Save the property

The system will automatically generate the appropriate embed code based on the URL or use your custom embed code.

### 2. Live Virtual Tours

Properties can enable live virtual tours, allowing users to schedule real-time video calls with agents who guide them through the property.

#### Enabling Live Tours

1. In the property admin panel, toggle "Live Virtual Tours Available" to enable
2. Users will see a "Schedule Live Tour" button on the property detail page
3. They can select a date, time, and add notes for the tour

#### Scheduling Process

When a user schedules a live tour:
1. An appointment is created with type "Live Virtual Tour"
2. The appointment is linked to the user, property, and assigned agent
3. A lead entry is created for tracking purposes
4. The user receives confirmation (handled by email notifications)

## Technical Implementation

### Database Schema

New fields added to `properties` table:
- `virtual_tour_url` (string, nullable) - URL to the virtual tour
- `virtual_tour_provider` (string, nullable) - Provider name (matterport, kuula, etc.)
- `virtual_tour_embed_code` (text, nullable) - Custom embed code if needed
- `live_tour_available` (boolean, default false) - Whether live tours are available

### Models

#### Property Model

New methods:
- `hasVirtualTour()` - Check if property has a virtual tour
- `getVirtualTourEmbed()` - Get the embed HTML for the virtual tour
- `generateEmbedCode($url)` - Auto-generate embed code from URL

### Components

#### PropertyDetail Livewire Component

New features:
- Toggle virtual tour display
- Schedule live virtual tour modal
- Form validation for tour scheduling
- Appointment creation logic

### Views

The property detail page now includes:
- Virtual tour section with toggle button
- Embedded iframe for virtual tours
- "Schedule Live Tour" button (when available)
- Modal for scheduling live tours with date/time picker

## User Experience

### For Property Viewers

1. **Viewing Virtual Tours**
   - Click "View Virtual Tour" button on property detail page
   - Explore the property in 3D at your own pace
   - Use provider's navigation tools (often VR-enabled)
   - Works seamlessly on desktop, tablet, and mobile devices

2. **Scheduling Live Tours**
   - Click "Schedule Live Tour" button
   - Select preferred date and time
   - Add any specific questions or areas of interest
   - Receive confirmation and appointment details

### For Agents/Administrators

1. **Adding Virtual Tours**
   - Simply paste the tour URL from your provider
   - System handles the technical details automatically
   - Toggle live tours on/off per property

2. **Managing Appointments**
   - View scheduled live tours in the appointments section
   - Receive notifications for new tour requests
   - Prepare for tours based on user notes

## Best Practices

### Virtual Tour URLs

- Use the shareable/embed URL from your provider
- For Matterport: Use URLs like `https://my.matterport.com/show/?m=XXXXX`
- For Kuula: Use URLs like `https://kuula.co/share/XXXXX`
- Test the embed before publishing

### Live Tours

- Ensure property agents are available for scheduled times
- Respond promptly to tour requests
- Use the notes field to prepare for specific user interests
- Have property information readily available during tours

## Responsive Design

The virtual tour features are fully responsive:
- Desktop: Full-size embedded tours with optimal viewing
- Tablet: Touch-optimized controls, appropriate sizing
- Mobile: Streamlined interface, portrait/landscape support
- All devices: Smooth loading and performance

## Browser Compatibility

Tested and working on:
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Appointment Types

Two new appointment types are seeded:
1. **Live Virtual Tour** - Real-time guided tour with an agent
2. **Self-Guided Virtual Tour** - Independent exploration of 3D tour

## Future Enhancements

Potential improvements:
- Calendar integration for agents
- Automated reminders for scheduled tours
- Video call integration (Zoom, Teams, etc.)
- Tour analytics (view time, areas of interest)
- VR headset support
- Multi-property tour scheduling

## Support

For issues or questions:
- Check provider documentation for embed URL formats
- Verify iframe compatibility with your provider
- Test on multiple devices before going live
- Contact system administrator for technical support
